<?php

namespace App\ValueObject;

use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Type;

class Pagination
{
    public const FIRST_PAGE = 1;
    public const DEFAULT_LIMIT= 10;

    #[Type('int')]
    #[Positive]
    public $page = self::FIRST_PAGE;

    #[Type('int')]
    #[Positive]
    public $limit = self::DEFAULT_LIMIT;
}
