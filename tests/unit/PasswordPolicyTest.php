<?php

namespace App\Tests\Unit;

use App\Security\PasswordPolicy;
use App\Tests\UnitTester;
use Codeception\Attribute\DataProvider;
use Codeception\Test\Unit;

class PasswordPolicyTest extends Unit
{
    protected UnitTester $tester;

    #[DataProvider('validPasswordsDataProvider')]
    public function testMeetsPasswordPolicy(string $password): void
    {
        $this->tester->assertTrue(PasswordPolicy::isMet($password));
    }

    #[DataProvider('invalidPasswordsDataProvider')]
    public function testNotMeetsPasswordPolicy(string $password): void
    {
        $this->tester->assertFalse(PasswordPolicy::isMet($password));
    }

    private function validPasswordsDataProvider(): array
    {
        return [
            ['Loream1!'],
            ['`Loream0'],
            ['L?o0ream'],
            ['L?0oream'],
            ['l.0oReam'],
            ['QwertyLorem123~'],
            ['#DolorSitAmet997^'],
        ];
    }

    private function invalidPasswordsDataProvider(): array
    {
        return [
            ['qwerty'],
            ['qwertY'],
            ['QWERTY'],
            ['QWErty1'],
            ['QWErty%'],
            ['123456789'],
            ['1234'],
            ['123456789*'],
            ['123456789a'],
            ['123456789A'],
            ['123456789Ab'],
            ['123456789A('],
            ['123456]789a'],
        ];
    }
}
