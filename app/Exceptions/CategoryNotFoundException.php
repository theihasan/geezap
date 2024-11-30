<?php

namespace App\Exceptions;

use Exception;

class CategoryNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('No job categories are available. Please add categories before creating a new job listing.');
    }
}
