<?php

namespace App\Contract;

interface QueryInterface
{
    public function getPagination(): PaginationInterface;
}
