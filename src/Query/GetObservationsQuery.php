<?php

namespace App\Query;

use App\Contract\GetObservationsQueryInterface;
use App\Contract\PaginationInterface;
use App\ValueObject\Pagination;
use Symfony\Component\Validator\Constraints\Type;

class GetObservationsQuery implements GetObservationsQueryInterface
{
    #[Type(Pagination::class)]
    public PaginationInterface $pagination;

    public function getPagination(): PaginationInterface
    {
        return $this->pagination;
    }
}
