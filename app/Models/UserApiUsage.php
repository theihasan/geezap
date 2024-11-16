<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserApiUsage extends Model
{

    protected $fillable = [
        'user_id',
        'api_type',
        'was_successful',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
