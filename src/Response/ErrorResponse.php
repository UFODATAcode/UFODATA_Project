<?php

namespace App\Response;

use App\ValueObject\Error;

class ErrorResponse implements \JsonSerializable
{
    /**
     * @var Error[]
     */
    private array $errors = [];

    public function addError(Error $error): void
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
