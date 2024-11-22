<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Airesponse extends Model
{
    protected $fillable = [
        'user_id',
        'job_id',
        'response',
    ];

    public function user(): BelongsTo
    {
      return $this->belongsTo(User::class);
    }

    public function job(): BelongsTo
    {
      return $this->belongsTo(JobListing::class, 'id');
    }
}
