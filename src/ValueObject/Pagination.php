<?php

namespace App\ValueObject;

use App\Contract\PaginationInterface;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Type;

class Pagination implements PaginationInterface, \JsonSerializable
{
    public const FIRST_PAGE = 1;
    public const DEFAULT_LIMIT= 10;
    public const DEFAULT_NUMBER_OF_PAGES = 1;

    #[Type('int')]
    #[Positive]
    #[OA\Property(property: 'page')]
    private $currentPage = self::FIRST_PAGE;

    #[Type('int')]
    #[Positive]
    private $limit = self::DEFAULT_LIMIT;

    private $numberOfPages = self::DEFAULT_NUMBER_OF_PAGES;

    #[Ignore]
    public function getSqlOffset(): int
    {
        return ($this->currentPage - 1) * $this->limit;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function setCurrentPage(int $currentPage): int
    {
        return $this->currentPage = $currentPage;
    }

    public function getNumberOfPages(): int
    {
        return $this->numberOfPages;
    }

    public function setNumberOfPages(int $numberOfPages): void
    {
        $this->numberOfPages = $numberOfPages;
    }

    public function jsonSerialize(): array
    {
        return [
            'limit' => $this->getLimit(),
            'page' => $this->getCurrentPage(),
            'numberOfPages' => $this->getNumberOfPages(),
        ];
    }
}
