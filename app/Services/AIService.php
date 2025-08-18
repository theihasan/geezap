<?php

namespace App\Services;

use App\Exceptions\DailyChatLimitExceededException;
use App\Exceptions\OpenAPICreditExceedException;
use App\Exceptions\OpenAIApiKeyInvalidException;
use App\Models\Airesponse;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    const DAILY_LIMIT = 20;

    private function checkUserLimit(User $user): bool
    {
        $todayResponses = Airesponse::query()
            ->where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->count();

        return $todayResponses < self::DAILY_LIMIT;
    }

    /**
     * Generate cover letter with streaming response
     * @throws \Throwable
     */
    public function generateCoverLetter(User $user, array $jobData, ?string $feedback = null, ?string $previousLetter = null): \Generator
    {
        throw_if(!$this->checkUserLimit($user), new DailyChatLimitExceededException("You've reached your daily limit of " . self::DAILY_LIMIT . " cover letter generations"));

        $messages = $this->buildMessages($user, $jobData, $feedback, $previousLetter);

        $response = Http::openai()
            ->withOptions(['stream' => true])
            ->withHeaders(['Accept' => 'text/event-stream'])
            ->post('completions', [
                'model' => 'gpt-3.5-turbo-16k',
                'messages' => $messages,
                'temperature' => 0.7,
                'stream' => true,
                'max_tokens' => 1000,
                'presence_penalty' => 0.6,
                'frequency_penalty' => 0.5
            ]);

        // Handle specific API errors
        if ($response->status() === 401) {
            throw new OpenAIApiKeyInvalidException();
        }
        
        throw_if($response->status() === 429, new OpenAPICreditExceedException());
        
        if (!$response->successful()) {
            Log::error('OpenAI API request failed', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            throw new \Exception('Failed to generate cover letter. Please try again.');
        }

        $stream = $response->getBody();
        $fullResponse = '';

        while (!$stream->eof()) {
            $chunk = $stream->read(1024);
            $lines = explode("\n", $chunk);

            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line) || $line === 'data: [DONE]') continue;

                if (str_starts_with($line, 'data: ')) {
                    $json = substr($line, 6);
                    $data = json_decode($json, true);

                    if (isset($data['choices'][0]['delta']['content'])) {
                        $text = $data['choices'][0]['delta']['content'];
                        $fullResponse .= $text;
                        yield $text;
                    }
                }
            }
        }

        // Store the complete response
        Airesponse::create([
            'user_id' => $user->id,
            'response' => $fullResponse,
            'job_id' => $jobData['id'] ?? null
        ]);
    }

    private function buildMessages(User $user, array $jobData, ?string $feedback, ?string $previousLetter): array
    {
        $messages = [
            [
                'role' => 'system',
                'content' => 'You are a professional cover letter writer. Create compelling, personalized cover letters that highlight the candidate\'s relevant experience and skills. Always write in a professional tone and format the letter properly.'
            ]
        ];

        if ($feedback && $previousLetter) {
            // Add conversation context for feedback
            $messages[] = ['role' => 'user', 'content' => $this->buildPrompt($user, $jobData)];
            $messages[] = ['role' => 'assistant', 'content' => $previousLetter];
            $messages[] = ['role' => 'user', 'content' => "Please improve this cover letter based on this feedback: {$feedback}. Keep the same professional tone but incorporate the requested changes."];
        } else {
            $messages[] = ['role' => 'user', 'content' => $this->buildPrompt($user, $jobData)];
        }

        return $messages;
    }

    private function buildPrompt(User $user, array $jobData): string
    {
        $skills = is_array($user->skills) ? implode(', ', $user->skills) : ($user->skills ?? '');
        $experience = $this->formatExperience($user->experience);

        return <<<PROMPT
        Create a professional cover letter for the following job opportunity:

        Job Details:
        - Position: {$jobData['job_title']}
        - Company: {$jobData['employer_name']}
        - Description: {$jobData['description']}

        Candidate Information:
        - Name: {$user->name}
        - Current Role: {$user->occupation}
        - Location: {$user->state}, {$user->country}
        - Skills: {$skills}
        - Experience: {$experience}
        - Bio: {$user->bio}

        Requirements:
        1. Address the hiring manager professionally
        2. Mention the specific position and company
        3. Highlight relevant skills and experience that match the job requirements
        4. Show enthusiasm for the role and company
        5. Include a strong closing with call to action
        6. Keep it concise (300-400 words)
        7. Format with proper paragraphs and spacing
        8. Use professional language throughout

        Generate a complete, ready-to-send cover letter.
        PROMPT;
    }

    private function formatExperience($experience): string
    {
        if (!is_array($experience)) {
            return $experience ?? '';
        }

        $formatted = [];
        foreach ($experience as $exp) {
            if (isset($exp['title'], $exp['company'])) {
                $duration = '';
                if (isset($exp['start_date'])) {
                    $duration = $exp['start_date'];
                    if (isset($exp['end_date'])) {
                        $duration .= " - " . $exp['end_date'];
                    }
                }
                $formatted[] = "{$exp['title']} at {$exp['company']}" . ($duration ? " ({$duration})" : '');
            }
        }
        return implode('; ', $formatted);
    }
}
