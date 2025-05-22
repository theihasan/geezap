<?php

namespace App\Services\AI;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use App\Exceptions\OpenAPICreditExceedException;
use Illuminate\Support\Facades\Log;

class OpenAIService extends BaseAIService
{
    protected function processResponse(array $messages, callable $callback): string
    {
        try {
            try {
                $response = Http::openai()
                    ->withOptions(['stream' => true])
                    ->withHeaders(['Accept' => 'text/event-stream'])
                    ->post('/completions', [
                        'model' => 'gpt-3.5-turbo-16k',
                        'messages' => $messages,
                        'temperature' => 0.7,
                        'stream' => true,
                        'max_tokens' => 1000,
                        'presence_penalty' => 0.6,
                        'frequency_penalty' => 0.5
                    ]);

                if ($response->failed()) {
                    $errorBody = $response->json();

                    if ($response->status() === 429 && isset($errorBody['error']['code']) && $errorBody['error']['code'] === 'insufficient_quota') {
                        throw new OpenAPICreditExceedException('OpenAI API quota exceeded');
                    }

                    throw new \RuntimeException('Failed to get response from OpenAI: ' . ($errorBody['error']['message'] ?? 'Unknown error'));
                }

                return $this->handleStream($response->getBody(), $callback);

            } catch (\Illuminate\Http\Client\RequestException $e) {
                Log::error('HTTP Request Exception', [
                    'message' => $e->getMessage(),
                    'response' => $e->response?->json()
                ]);
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('OpenAI Service Fatal Error', [
                'error' => $e->getMessage(),
                'class' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    protected function handleStream($stream, callable $callback): string
    {
        try {
            $buffer = '';
            $fullResponse = '';

            while (!$stream->eof()) {
                try {
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

                                if ($this->shouldFlushBuffer($buffer)) {
                                    $callback($buffer);
                                    $buffer = '';
                                }
                            }
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Stream processing error', [
                        'error' => $e->getMessage(),
                        'position' => ftell($stream)
                    ]);
                    throw $e;
                }
            }

            if (!empty($buffer)) {
                $callback($buffer);
            }

            return $fullResponse;

        } catch (\Exception $e) {
            Log::error('Stream handling error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
    protected function shouldFlushBuffer(string $buffer): bool
    {
        return str_ends_with($buffer, '.') ||
            str_ends_with($buffer, '!') ||
            str_ends_with($buffer, '?') ||
            str_ends_with($buffer, "\n") ||
            strlen($buffer) > 100;
    }

    protected function getPromptTemplate(User $user, array $jobData, string $skills, string $experience): string
    {
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
