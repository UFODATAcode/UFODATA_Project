<?php

namespace App\Response;

use App\ValueObject\Pagination;
use JsonSerializable;

class GetResourcesResponse implements JsonSerializable
{
    public function __construct(
        /** @var JsonSerializable[] $resources */
        private readonly array $resources,
        private readonly Pagination $pagination
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'data' => $this->resources,
            'pagination' => $this->pagination,
        ];
    }
}
