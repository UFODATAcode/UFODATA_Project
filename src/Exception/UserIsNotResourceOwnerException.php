<?php

namespace App\Exception;

class UserIsNotResourceOwnerException extends ActionDeniedException
{
    public function __construct()
    {
        parent::__construct('User is not resource owner.', '4bcf7afc-662b-438c-9a0a-6822dd608b75');
    }
}
