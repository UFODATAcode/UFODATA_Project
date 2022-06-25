<?php

namespace App\Response;

use App\ValueObject\Pagination;

class GetObservationsResponse implements \JsonSerializable
{
    public function __construct(
        private readonly array $observations,
        private readonly Pagination $pagination
    ) {}


    public function jsonSerialize(): array
    {
        return [
            'data' => $this->observations,
            'pagination' => $this->pagination,
        ];
    }
}
