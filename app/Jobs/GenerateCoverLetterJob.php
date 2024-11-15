<?php

namespace App\Jobs;

use App\Events\CoverLetterGenerated;
use App\Models\User;
use App\Models\UserApiUsage;
use App\Services\AIService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateCoverLetterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private const DAILY_LIMIT = 50;

    public function __construct(private readonly User $user, private readonly array $requestData)
    {

    }


    public function handle(AIService $aiService): array
    {
        try {
            if (!$this->checkUsageLimit()) {
                return [
                    'message' => 'Daily limit exceeded. Please try again after 24 hours.',
                    'status' => 429,
                    'data' => [
                        'daily_requests' => $this->getDailyUsageCount(),
                        'limit' => self::DAILY_LIMIT
                    ]
                ];
            }


            $response = $this->alternateAIProviders($aiService);
            logger("AI response received", $response);

            if ($response['status'] === 200 && !empty($response['data']['response'])) {
                logger("Broadcasting event for user: {$this->user->id}", [
                    'data' => $response['data']
                ]);
                event(new CoverLetterGenerated($this->user, $response['data']));
            }

            return $response;



        } catch (\Exception $e) {

            $this->recordUsage(false);

            return [
                'message' => 'Failed to generate cover letter: ' . $e->getMessage(),
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }
    }

    private function alternateAIProviders(AIService $aiService): array
    {
        $dailyCount = $this->getDailyUsageCount();
        return  $aiService->getChatGPTResponse($this->user, $this->requestData);
    }

    private function checkUsageLimit(): bool
    {
        return $this->getDailyUsageCount() < self::DAILY_LIMIT;
    }

    private function getDailyUsageCount(): int
    {
        return UserApiUsage::query()->where('user_id', $this->user->id)
            ->where('created_at', '>=', now()->subDay())
            ->count();
    }

    private function recordUsage(bool $wasSuccessful): void
    {
        UserApiUsage::query()->create([
            'user_id' => $this->user->id,
            'api_type' => 'cover_letter',
            'was_successful' => $wasSuccessful
        ]);
    }
}
