<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\Collection;

class CategoryNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('No job categories are available. Please add categories before creating a new job listing.');
    }

    public static function throwIfNotFound(Collection $categories)
    {
        if ($categories->count() === 0) {
            throw new self();
        }
    }
}
