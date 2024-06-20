<?php

namespace App\Services;

use App\Constants\CoverLetterPrompt;
use App\Models\User;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    private int $counter;
    private array $counterTimestamps;
    private array $coverLetters;

    public function generateCoverLetter(Request $request): array
    {
        $this->counterTimestamps = session('counter_timestamps', []);
        $this->coverLetters = session('cover_letters', []);

        $this->counter = count($this->counterTimestamps);
        $this->cleanupOldTimestamps();

        if ($this->counter >= 50) {
            return $this->getExceededLimitResponse();
        }

        $this->addNewTimestamp();

        if ($this->counter % 2 === 0) {
            $response = $this->getChatGPTResponse($request);
        } else {
            $response = $this->getGeminiResponse($request);
        }

        if (isset($response['data']['response'])) {
            $this->saveCoverLetter($response['data']['response']);
        }

        session(['cover_letters' => $this->coverLetters]);

        return $response;
    }

    private function cleanupOldTimestamps(): void
    {
        $currentTime = time();
        $this->counterTimestamps = array_filter($this->counterTimestamps, function ($timestamp) use ($currentTime) {
            return ($currentTime - $timestamp) <= (24 * 60 * 60);
        });

        session(['counter_timestamps' => $this->counterTimestamps]);
        $this->counter = count($this->counterTimestamps);
    }

    private function addNewTimestamp(): void
    {
        $this->counterTimestamps[] = time();
        session(['counter_timestamps' => $this->counterTimestamps]);
        $this->counter = count($this->counterTimestamps);
    }

    private function getExceededLimitResponse(): array
    {
        return [
            'message' => 'Your cover letter generation limit has been exceeded. Please try again after 24 hours.',
            'data' => [
                'counter' => $this->counter,
            ],
        ];
    }

    private function generatePrompt(User $user, Request $request): string
    {
        $jobTitle = $request->input('job_title', '');
        $companyName = $request->input('employer_name', '');
        $employerLocation = $request->input('employer_location', '');
        $jobDescription = $request->input('job_description', '');

        // Check if all required data is available
        if (empty($jobTitle) || empty($companyName) || empty($employerLocation) || empty($jobDescription)) {
            throw new \Exception('One or more required fields are missing.');
        }

        $experience = is_array($user->experience) ? implode(', ', $user->experience) : $user->experience;
        $skills = is_array($user->skills) ? implode(', ', $user->skills) : $user->skills;

        $prompt = CoverLetterPrompt::getRandomPrompt();
        $prompt = str_replace('[Name]', $user->name, $prompt);
        $prompt = str_replace('[JobTitle]', $jobTitle, $prompt);
        $prompt = str_replace('[CompanyName]', $companyName, $prompt);
        $prompt = str_replace('[JobDescription]', $jobDescription, $prompt);
        $prompt = str_replace('[Experience]', $experience, $prompt);
        $prompt = str_replace('[Skills]', $skills, $prompt);

        return $prompt;
    }

    private function getGeminiResponse(Request $request): array
    {
        try {
            $user = User::find(auth()->id());
            if (!$user) {
                throw new \Exception('User not found.');
            }

            $prompt = $this->generatePrompt($user, $request);

            Log::info('Sending request to Gemini API', ['prompt' => $prompt]);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=' . config('ai.gemini_api_key'), [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => $prompt,
                            ]
                        ]
                    ]
                ]
            ]);

            Log::info('Gemini API response', ['response' => $response->body()]);

            return [
                'message' => 'Gemini API is working',
                'status' => 200,
                'data' => [
                    'response' => $response->json('candidates.0.content.parts.0.text'),
                    'counter' => $this->counter,
                ],
            ];
        } catch (\Exception $exception) {
            Log::error('Gemini API error', ['exception' => $exception]);
            return $this->getGeminiErrorResponse($exception);
        }
    }

    private function getGeminiErrorResponse(\Exception $exception): array
    {
        return [
            'message' => 'Gemini API is not working',
            'status' => 500,
            'error' => $exception->getMessage(),
            'counter' => $this->counter,
        ];
    }

    private function getChatGPTResponse(Request $request): array
    {
        try {
            $user = User::find(auth()->id());
            if (!$user) {
                throw new \Exception('User not found.');
            }

            $prompt = $this->generatePrompt($user, $request);

            Log::info('Sending request to ChatGPT API', ['prompt' => $prompt]);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . config('ai.chat_gpt_api_key'),
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo-16k',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a helpful assistant.',
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
            ]);

            Log::info('ChatGPT API response', ['response' => $response->body()]);

            return [
                'message' => 'Chat GPT API is working',
                'status' => 200,
                'data' => [
                    'response' => $response->json('choices.0.message.content'),
                    'counter' => $this->counter,
                ],
            ];
        } catch (\Exception $exception) {
            Log::error('ChatGPT API error', ['exception' => $exception]);
            return $this->getChatGPTErrorResponse($exception);
        }
    }

    private function getChatGPTErrorResponse(\Exception $exception): array
    {
        return [
            'message' => 'Chat GPT API is not working',
            'status' => 500,
            'error' => $exception->getMessage(),
            'counter' => $this->counter,
        ];
    }

    private function saveCoverLetter(string $coverLetter): void
    {
        $this->coverLetters[] = $coverLetter;
        session(['cover_letters' => $this->coverLetters]);
    }
}
