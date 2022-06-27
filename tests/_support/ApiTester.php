<?php

declare(strict_types=1);
namespace App\Tests;

use App\Entity\User;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
*/
class ApiTester extends \Codeception\Actor
{
    use _generated\ApiTesterActions;

    public function setBearerTokenForUser(string $userEmail): void
    {
        $this->amBearerAuthenticated($this->grabService('lexik_jwt_authentication.jwt_manager')->create(
            $this->grabEntityFromRepository(User::class, ['email' => $userEmail])
        ));
    }
}
