<?php

namespace App\Enum;

enum Role: string
{
    case Admin = 'ROLE_ADMIN';
    case User = 'ROLE_USER';

    /**
     * @return string[]
     */
    public static function values(): array
    {
        return \array_map(fn($case) => $case->value, self::cases());
    }
}
