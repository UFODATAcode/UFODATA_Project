<?php

namespace App\Tests\Api;

use App\Entity\User;
use App\Entity\UserActivationLink;
use App\Tests\ApiTester;
use Symfony\Component\HttpFoundation\Response;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertStringContainsString;

class RegisterUserCest
{
    public function iCanRegisterAsANewUserReceiveAnEmailWithAnAccountActivationLinkAndActivateIt(ApiTester $I): void
    {
        $newUserUuid = 'e48cb79c-5c93-4f57-b9c0-69cff527cf70';

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost(
            '/users/register',
            [
                'uuid' => $newUserUuid,
                'email' => 'new@user.com',
                'name' => 'nuser',
                'password' => 'strongpwd',
            ]
        );

        $I->seeResponseCodeIs(Response::HTTP_NO_CONTENT);
        $I->seeInRepository(
            User::class,
            [
                'uuid' => $newUserUuid,
                'email' => 'new@user.com',
                'name' => 'nuser',
                'active' => false,
            ]
        );

        /*
         * For tests purposes an event responsible for triggering email sending is synchronous.
         */
        $I->seeEmailIsSent();

        $accountActivationEmail = $I->grabLastSentEmail();
        assertNotNull($accountActivationEmail);
        assertStringContainsString('Thank you for registration!', $accountActivationEmail->getTextBody());

        $user = $I->grabEntityFromRepository(User::class, ['uuid' => $newUserUuid]);
        $activationLink = $I->grabEntityFromRepository(UserActivationLink::class, ['user' => $user->getId()]);

        assertNotNull($activationLink);

        $I->sendGet('/users/activate/' . $activationLink->getId());

        $I->seeResponseCodeIs(Response::HTTP_NO_CONTENT);
        $I->seeInRepository(
            User::class,
            [
                'uuid' => $newUserUuid,
                'email' => 'new@user.com',
                'name' => 'nuser',
                'active' => true,
            ]
        );
    }
}
