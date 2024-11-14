<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{
    protected $fillable = ['api_key', 'api_secret', 'api_name', 'request_remaining'];

    protected function casts()
    {
        return [

        ];
    }
}
