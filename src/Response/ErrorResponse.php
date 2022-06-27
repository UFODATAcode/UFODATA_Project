<?php

namespace App\Response;

use App\ValueObject\ValidationError;

class ErrorResponse implements \JsonSerializable
{
    /**
     * @var ValidationError[]
     */
    private array $errors = [];

    public function addError(ValidationError $error): void
    {
        $this->errors[] = $error;
    }

    public function jsonSerialize(): array
    {
        return [
            'errors' => $this->errors,
        ];
    }
}
