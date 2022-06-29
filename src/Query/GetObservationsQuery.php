<?php

namespace App\Query;

use App\Contract\QueryInterface;
use App\ValueObject\Pagination;
use Symfony\Component\Validator\Constraints\Type;

class GetObservationsQuery implements QueryInterface
{
    #[Type(Pagination::class)]
    public $pagination;
}
