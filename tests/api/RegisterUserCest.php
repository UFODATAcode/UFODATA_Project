<?php

namespace App\Tests\Api;

use App\DataFixtures\RegisterUserFixtures;
use App\Entity\User;
use \App\Tests\ApiTester;
use Symfony\Component\HttpFoundation\Response;

class RegisterUserCest
{
    public function iCanRegisterInTheApplicationWhenIProvideValidData(ApiTester $I): void
    {
        $I->dontSeeInRepository(
            User::class,
            [
                'uuid' => RegisterUserFixtures::NOT_REGISTERED_USER_UUID,
                'name' => RegisterUserFixtures::NOT_REGISTERED_USER_NAME,
                'email' => RegisterUserFixtures::NOT_REGISTERED_USER_EMAIL,
            ],
        );
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost(
            '/register',
            [
                'uuid' => RegisterUserFixtures::NOT_REGISTERED_USER_UUID,
                'name' => RegisterUserFixtures::NOT_REGISTERED_USER_NAME,
                'email' => RegisterUserFixtures::NOT_REGISTERED_USER_EMAIL,
                'password' => 'test',
            ],
        );
        $I->seeResponseCodeIs(Response::HTTP_NO_CONTENT);
        $I->seeInRepository(
            User::class,
            [
                'uuid' => RegisterUserFixtures::NOT_REGISTERED_USER_UUID,
                'name' => RegisterUserFixtures::NOT_REGISTERED_USER_NAME,
                'email' => RegisterUserFixtures::NOT_REGISTERED_USER_EMAIL,
                'active' => false,
            ],
        );

    }
}
