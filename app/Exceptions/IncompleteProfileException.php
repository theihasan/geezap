<?php

namespace App\Exceptions;

use Exception;

class IncompleteProfileException extends Exception
{
   public function __construct(string $message = "Please complete your profile with skills and experience before generating a cover letter", int $code = 0, ?Throwable $previous = null)
   {
       parent::__construct($message, $code, $previous);
   }
}
