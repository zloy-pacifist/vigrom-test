<?php

namespace App\Exceptions;

use RuntimeException;
use Throwable;

class ValidationException extends RuntimeException
{
    public function __construct(
        private string $field,
        string $message,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getField(): string
    {
        return $this->field;
    }
}
