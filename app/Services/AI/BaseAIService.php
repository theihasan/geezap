<?php

namespace App\Services\AI;

use App\Models\User;
use App\Exceptions\DailyChatLimitExceededException;
use Illuminate\Support\Facades\Log;

abstract class BaseAIService
{
    protected const DAILY_LIMIT = 40;

    final public function getChatResponse(User $user, array $jobData, callable $callback, ?string $feedback = null, ?string $previousAnswer = null): string
    {
        try {
            Log::info('BaseAIService: Starting getChatResponse');

            Log::info('BaseAIService: Checking limits');
            $this->checkLimits($user);

            Log::info('BaseAIService: Preparing messages');
            $messages = $this->prepareMessages($user, $jobData, $feedback, $previousAnswer);

            Log::info('BaseAIService: Calling processResponse', [
                'messagesCount' => count($messages)
            ]);

            return $this->processResponse($messages, $callback);

        } catch (\Exception $e) {
            Log::error('BaseAIService: Error in getChatResponse', [
                'error' => $e->getMessage(),
                'class' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }


    protected function checkLimits(User $user): void
    {
        try {
            $todayResponses = $this->getUserDailyResponses($user);
            Log::info('BaseAIService: Checking limits', [
                'todayResponses' => $todayResponses,
                'limit' => static::DAILY_LIMIT
            ]);

            if ($todayResponses >= static::DAILY_LIMIT) {
                Log::warning('BaseAIService: Daily limit exceeded', [
                    'userId' => $user->id,
                    'limit' => static::DAILY_LIMIT
                ]);
                throw new DailyChatLimitExceededException();
            }
        } catch (\Exception $e) {
            Log::error('BaseAIService: Error in checkLimits', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }


    protected function prepareMessages(User $user, array $jobData, ?string $feedback, ?string $previousAnswer): array
    {
        $messages = [
            [
                'role' => 'system',
                'content' => $this->getSystemPrompt()
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
                'content' => $this->buildFeedbackPrompt($feedback)
            ];
        } else {
            $messages[] = [
                'role' => 'user',
                'content' => $this->buildPrompt($user, $jobData)
            ];
        }

        return $messages;
    }


    protected function getUserDailyResponses(User $user): int
    {
        try {
            $count = \App\Models\Airesponse::query()
                ->where('user_id', $user->id)
                ->whereDate('created_at', today())
                ->count();

            Log::info('BaseAIService: Daily responses count', ['count' => $count]);

            return $count;
        } catch (\Exception $e) {
            Log::error('BaseAIService: Error getting daily responses', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }


    protected function buildPrompt(User $user, array $jobData): string
    {
        $skills = is_array($user->skills) ? implode(', ', $user->skills) : '';

        $experienceData = is_string($user->experience)
            ? json_decode($user->experience, true)
            : $user->experience;

        if (json_last_error() !== JSON_ERROR_NONE && is_string($user->experience)) {
            Log::error('JSON decode error', [
                'error' => json_last_error_msg(),
                'experience' => $user->experience
            ]);
            $experienceData = [];
        }

        $experience = $this->formatExperience($experienceData ?? []);

        return $this->getPromptTemplate($user, $jobData, $skills, $experience);
    }

    protected function formatExperience(array $experience): string
    {
        $experienceItems = [];
        foreach ($experience as $exp) {
            if (isset($exp['title']) && isset($exp['company'])) {
                $duration = $this->formatDuration($exp);
                $experienceItems[] = "{$exp['title']} at {$exp['company']} ($duration)";
            }
        }
        return implode("\n", $experienceItems);
    }


    protected function formatDuration(array $exp): string
    {
        $duration = '';
        if (isset($exp['start_date'])) {
            $duration .= $exp['start_date'];
            if (isset($exp['end_date'])) {
                $duration .= " - " . $exp['end_date'];
            }
        }
        return $duration;
    }

    protected function buildFeedbackPrompt(string $feedback): string
    {
        return "Please improve the cover letter based on this feedback: {$feedback}. Keep the same professional tone but incorporate these changes.";
    }


    protected function getSystemPrompt(): string
    {
        return 'You are a professional CV writer helping to generate a cover letter. Create compelling, personalized cover letters that highlight the candidate\'s relevant experience and skills.';
    }

    abstract protected function processResponse(array $messages, callable $callback): string;
    abstract protected function getPromptTemplate(User $user, array $jobData, string $skills, string $experience): string;
}
