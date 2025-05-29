<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{
    protected $fillable = ['api_key', 'api_secret', 'api_name', 'request_remaining', 'request_sent_at'];

    protected function casts()
    {
        return [
            'request_sent_at' => 'datetime',
            'request_remaining' => 'integer'
        ];
    }
}
