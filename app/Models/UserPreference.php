<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPreference extends Model
{
    protected $fillable = [
        'user_id', 'session_id', 'email_frequency', 'emails_per_frequency',
        'preferred_job_categories_id', 'preferred_regions_id', 'preferred_job_types', 
        'preferred_experience_levels', 'min_salary', 'max_salary', 'remote_only', 
        'email_notifications_enabled', 'show_recommendations', 
        'last_recommendation_update'
    ];

    protected function casts(): array
    {
        return [
        'preferred_job_categories_id' => 'array',
        'preferred_regions_id' => 'array',
        'preferred_job_types' => 'array',
        'preferred_experience_levels' => 'array',
        'email_notifications_enabled' => 'boolean',
        'show_recommendations' => 'boolean',
        'remote_only' => 'boolean',
        'last_recommendation_update' => 'datetime',
        ];
   }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}