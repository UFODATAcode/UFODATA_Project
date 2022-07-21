<?php

namespace App\Contract;

interface FileUploadInterface
{
    /**
     * @return string[]
     */
    public static function getFilesNames(): array;
}
