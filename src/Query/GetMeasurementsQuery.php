<?php

namespace App\Query;

use App\Contract\GetMeasurementsQueryInterface;
use App\Contract\PaginationInterface;
use App\ValueObject\Pagination;
use Symfony\Component\Validator\Constraints\Type;

class GetMeasurementsQuery implements GetMeasurementsQueryInterface
{
    #[Type(Pagination::class)]
    public PaginationInterface $pagination;

    public function getPagination(): PaginationInterface
    {
        return $this->pagination;
    }
}
