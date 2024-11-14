<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class JobCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'query_name',
        'page',
        'num_page',
        'timeframe',
        'category_image',
    ];



    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->slug = Str::slug($model->name);
        });
    }
}
