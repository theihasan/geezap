<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplyOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_listing_id',
        'publisher',
        'apply_link',
        'is_direct',
    ];

    protected function casts(): array
    {
        return [
            'is_direct' => 'boolean',
        ];
    }

    public function jobListing(): BelongsTo
    {
        return $this->belongsTo(JobListing::class);
    }
}