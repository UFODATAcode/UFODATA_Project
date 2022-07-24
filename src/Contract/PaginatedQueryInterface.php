<?php

namespace App\Contract;

interface PaginatedQueryInterface extends QueryInterface
{
    public function getPagination(): PaginationInterface;
}
