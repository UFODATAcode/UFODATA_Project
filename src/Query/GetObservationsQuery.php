<?php

namespace App\Query;

use App\ValueObject\Pagination;
use Symfony\Component\Validator\Constraints\Type;

class GetObservationsQuery
{
    #[Type(Pagination::class)]
    public $pagination;
}
