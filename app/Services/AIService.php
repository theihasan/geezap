<?php

namespace App\Services;

use App\Constants\CoverLetterPrompt;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class AIService
{
    public function getChatGPTResponse(User $user, array $requestData): array
    {
        try {
            $prompt = $this->generatePrompt($user, $requestData);
            $apiKey = config('ai.chat_gpt_api_key');

            if (empty($apiKey)) {
                throw new \Exception('OpenAI API key is not configured');
            }

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $apiKey,
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
                'temperature' => 0.7,
                'max_tokens' => 1000,
            ]);

            if (!$response->successful()) {
                throw new \Exception('OpenAI API returned status code: ' . $response->status() . ' with body: ' . $response->body());
            }

            $responseData = $response->json('choices.0.message.content');


            if (!isset($responseData)) {
                //Sentry Implementation
                throw new \Exception('Unexpected response structure from OpenAI API: ' . json_encode($responseData));
            }

            if (empty($responseData)) {
                throw new \Exception('Empty content received from OpenAI API');
            }

            return [
                'message' => 'Cover letter generated successfully',
                'status' => 200,
                'data' => [
                    'response' => $responseData,
                ],
            ];

        } catch (\Exception $e) {
            return [
                'message' => 'Failed to generate cover letter: ' . $e->getMessage(),
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }
    }

    private function generatePrompt(User $user, array $requestData): string
    {

        if (empty($requestData['job_title']) ||
            empty($requestData['employer_name']) ||
            empty($requestData['description'])) {
            throw new \Exception('Missing required fields: job_title, employer_name, or job_description');
        }

        $experience = is_array($user->experience) ? implode(', ', $user->experience) : $user->experience;
        $skills = is_array($user->skills) ? implode(', ', $user->skills) : $user->skills;

        $prompt = CoverLetterPrompt::getRandomPrompt();

        $replacements = [
            '[JobTitle]' => $requestData['job_title'],
            '[CompanyName]' => $requestData['employer_name'],
            '[JobDescription]' => $requestData['description'],
            '[Experience]' => $experience,
            '[Skills]' => $skills,
            '[Name]' => $user->name
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $prompt);
    }


}
