<?php

namespace App\Contract;

interface PaginationInterface
{
    public function getSqlOffset(): int;
    public function getLimit(): int;
    public function setLimit(int $limit): void;
    public function getCurrentPage(): int;
    public function setCurrentPage(int $currentPage): int;
    public function getNumberOfPages(): int;
    public function setNumberOfPages(int $numberOfPages): void;
}
