<?php

namespace Geezap\ContentFormatter\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'status',
        'formatted_content',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function jobListing()
    {
        return $this->belongsTo(\App\Models\JobListing::class, 'metadata->job_listing_id', 'id');
    }

    public function getJobListingIdAttribute()
    {
        return $this->metadata['job_listing_id'] ?? null;
    }
}