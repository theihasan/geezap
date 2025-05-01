<?php

namespace App\Services;

use App\Exceptions\DailyChatLimitExceededException;
use App\Exceptions\OpenAPICreditExceedException;
use App\Models\Airesponse;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class AIService
{

    const DAILY_LIMIT = 5;

    private function checkUserLimit(User $user): bool
    {
        $todayResponses = Airesponse::query()
            ->where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->count();

        return $todayResponses < self::DAILY_LIMIT;
    }

    /**
     * @throws \Throwable
     */
    public function getChatResponse(User $user, array $jobData, callable $callback, ?string $feedback = null, ?string $previousAnswer = null): string
    {
        throw_if(! $this->checkUserLimit($user) , new DailyChatLimitExceededException("You've reached your daily limit of " . self::DAILY_LIMIT . " cover letter generations"));

        $messages = [
            [
                'role' => 'system',
                'content' => 'You are a professional CV writer helping to generate a cover letter. Create compelling, personalized cover letters that highlight the candidate\'s relevant experience and skills.'
            ]
        ];

        if ($feedback) {
            $messages[] = [
                'role' => 'user',
                'content' => $this->buildPrompt($user, $jobData)
            ];
            $messages[] = [
                'role' => 'assistant',
                'content' => $previousAnswer ?? ''
            ];
            $messages[] = [
                'role' => 'user',
                'content' => "Please improve the cover letter based on this feedback: {$feedback}. Keep the same professional tone but incorporate these changes."
            ];
        } else {
            $messages[] = [
                'role' => 'user',
                'content' => $this->buildPrompt($user, $jobData)
            ];
        }

        $response = Http::openai()
            ->withOptions([
                'stream' => true,
            ])
            ->withHeaders([
                'Accept' => 'text/event-stream',
            ])
            ->post('completions', [
                'model' => 'gpt-3.5-turbo-16k',
                'messages' => $messages,
                'temperature' => 0.7,
                'stream' => true,
                'max_tokens' => 1000,
                'presence_penalty' => 0.6,
                'frequency_penalty' => 0.5
            ]);
        throw_if($response->status() === 429, new OpenAPICreditExceedException());
        $buffer = '';
        $fullResponse = '';



        $stream = $response->getBody();

        while (!$stream->eof()) {
            $chunk = $stream->read(1024);
            $lines = explode("\n", $chunk);

            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line)) continue;
                if ($line === 'data: [DONE]') break;

                if (str_starts_with($line, 'data: ')) {
                    $json = substr($line, 6);
                    $data = json_decode($json, true);

                    if (isset($data['choices'][0]['delta']['content'])) {
                        $text = $data['choices'][0]['delta']['content'];
                        $buffer .= $text;
                        $fullResponse .= $text;

                        if (str_ends_with($buffer, '.') ||
                            str_ends_with($buffer, '!') ||
                            str_ends_with($buffer, '?') ||
                            str_ends_with($buffer, "\n") ||
                            strlen($buffer) > 100) {
                            $callback($buffer);
                            $buffer = '';
                        }
                    }
                }
            }
        }

        if (!empty($buffer)) {
            $callback($buffer);
        }

        return $fullResponse;
    }

    private function buildPrompt(User $user, array $jobData): string
    {
        $skills = is_array($user->skills) ? implode(', ', $user->skills) : '';

        $experience = '';
        if (is_array($user->experience)) {
            $experienceItems = [];
            foreach ($user->experience as $exp) {
                if (isset($exp['title']) && isset($exp['company'])) {
                    $duration = '';
                    if (isset($exp['start_date'])) {
                        $duration .= $exp['start_date'];
                        if (isset($exp['end_date'])) {
                            $duration .= " - " . $exp['end_date'];
                        }
                    }
                    $experienceItems[] = "{$exp['title']} at {$exp['company']} ($duration)";
                }
            }
            $experience = implode("\n", $experienceItems);
        }

        return <<<EOT
        Create a professional and engaging cover letter for the following job opportunity. The cover letter should be well-structured and highlight the candidate's relevant experience and skills.

        Job Details:
        Title: {$jobData['job_title']}
        Company: {$jobData['employer_name']}
        Description: {$jobData['description']}

        Candidate Profile:
        Name: {$user->name}
        Current Role: {$user->occupation}
        Location: {$user->state}, {$user->country}

        Key Skills:
        {$skills}

        Professional Experience:
        {$experience}

        Additional Information:
        {$user->bio}

        Guidelines:
        1. Start with a strong opening paragraph that mentions the specific role and company
        2. Demonstrate understanding of the company's needs and how the candidate's experience matches them
        3. Use specific examples from the candidate's experience to demonstrate relevant skills
        4. Keep a professional yet enthusiastic tone
        5. Include a strong closing paragraph expressing interest in next steps
        6. Format the letter properly with appropriate spacing and paragraphs
        7. Ensure the letter is concise but comprehensive (around 300-400 words)
        8. Highlight key achievements and skills that directly relate to the job requirements

        Note: Focus on creating a personalized letter that shows why this candidate is uniquely qualified for this specific role.
        EOT;
    }
}
