<?php

namespace App\Security;

class PasswordPolicy
{
    public const MINIMUM_LENGTH = 8;
    private const ALLOWED_SPECIAL_CHARACTERS = '/[!@#\$%^&*()_\-+={\[}\]|"\':\\\;?\/>.<,~`£§]/';

    private function __construct()
    {
    }

    public static function isMet(string $password): bool
    {
        return self::isLongEnough($password)
            && self::containsDigit($password)
            && self::containsUpperCase($password)
            && self::containsLowerCase($password)
            && self::containsSpecialCharacter($password);
    }

    private static function isLongEnough(string $password): bool
    {
        return \mb_strlen($password) >= self::MINIMUM_LENGTH;
    }

    private static function containsSpecialCharacter(string $password): bool
    {
        return (bool) \preg_match(self::ALLOWED_SPECIAL_CHARACTERS, $password);
    }

    private static function containsDigit(string $password): bool
    {
        return (bool) \preg_match('/\d/', $password);
    }

    private static function containsUpperCase(string $password): bool
    {
        return (bool) \preg_match('/[A-Z]/', $password);
    }

    private static function containsLowerCase(string $password): bool
    {
        return (bool) \preg_match('/[a-z]/', $password);
    }
}