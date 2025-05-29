<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestPreference extends Model
{
    protected $fillable = [
        'session_id', 'email', 'preferred_job_topics', 'preferred_regions',
        'preferred_job_types', 'remote_only', 'email_alerts_enabled',
        'daily_views', 'last_view_date'
    ];

    protected function casts(): array
    {
        return [
            'preferred_job_topics' => 'array',
            'preferred_regions' => 'array',
            'preferred_job_types' => 'array',
            'email_alerts_enabled' => 'boolean',
            'remote_only' => 'boolean',
            'last_view_date' => 'date',
        ];
    }

    public function hasReachedDailyLimit(): bool
    {
        return $this->daily_views >= 10 && $this->last_view_date?->isToday();
    }

    public function incrementViews(): void
    {
        if (!$this->last_view_date?->isToday()) {
            $this->daily_views = 0;
            $this->last_view_date = now()->toDateString();
        }
        $this->increment('daily_views');
    }
}