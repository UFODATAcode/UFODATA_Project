<?php

namespace App\Query;

use App\Contract\GetMeasurementsPaginatedQueryInterface;
use App\Contract\PaginationInterface;
use App\ValueObject\Pagination;
use Symfony\Component\Validator\Constraints\Type;

class GetMeasurementsQuery implements GetMeasurementsPaginatedQueryInterface
{
    #[Type(Pagination::class)]
    public PaginationInterface $pagination;

    public function getPagination(): PaginationInterface
    {
        return $this->pagination;
    }
}
