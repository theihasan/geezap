<?php

namespace App\Models;

use App\Helpers\CountryFlag;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = ['name', 'code', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Get the flag emoji for this country
     */
    public function getFlag(): string
    {
        return CountryFlag::getFlag($this->code);
    }
}
