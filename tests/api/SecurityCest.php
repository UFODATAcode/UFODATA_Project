<?php

namespace App\Tests\Api;

use App\DataFixtures\SecurityFixtures;
use \App\Tests\ApiTester;
use Symfony\Component\HttpFoundation\Response;

class SecurityCest
{
    public function iCanObtainAnAuthorizationTokenWhenIHaveAnActiveAccount(ApiTester $I): void
    {
        $I->loadFixtures(SecurityFixtures::class);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost(
            '/login_check',
            [
                'username' => SecurityFixtures::REGISTERED_ACTIVE_USER_EMAIL,
                'password' => SecurityFixtures::REGISTERED_ACTIVE_USER_PASSWORD,
            ],
        );
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseContains('token');
    }

    public function iCanNotObtainAnAuthorizationTokenWhenIAmNotRegistered(ApiTester $I): void
    {
        $I->loadFixtures(SecurityFixtures::class);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost(
            '/login_check',
            [
                'username' => SecurityFixtures::NOT_REGISTERED_USER_EMAIL,
                'password' => SecurityFixtures::NOT_REGISTERED_USER_EMAIL,
            ],
        );
        $I->seeResponseCodeIs(Response::HTTP_UNAUTHORIZED);
        $I->seeResponseContainsJson([
            'code' => 401,
            'message' => 'Invalid credentials.',
        ]);
    }

    public function iCanNotObtainAnAuthorizationTokenWhenIUseWrongPassword(ApiTester $I): void
    {
        $I->loadFixtures(SecurityFixtures::class);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost(
            '/login_check',
            [
                'username' => SecurityFixtures::REGISTERED_ACTIVE_USER_EMAIL,
                'password' => 'invalidpassword',
            ],
        );
        $I->seeResponseCodeIs(Response::HTTP_UNAUTHORIZED);
        $I->seeResponseContainsJson([
            'code' => 401,
            'message' => 'Invalid credentials.',
        ]);
    }

    public function iCanNotObtainAnAuthorizationTokenWhenIUseWrongUsername(ApiTester $I): void
    {
        $I->loadFixtures(SecurityFixtures::class);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost(
            '/login_check',
            [
                'username' => 'wrongusername',
                'password' => SecurityFixtures::REGISTERED_ACTIVE_USER_PASSWORD,
            ],
        );
        $I->seeResponseCodeIs(Response::HTTP_UNAUTHORIZED);
        $I->seeResponseContainsJson([
            'code' => 401,
            'message' => 'Invalid credentials.',
        ]);
    }

    public function iCanNotObtainAnAuthorizationTokenWhenIHaveAnInactiveAccount(ApiTester $I): void
    {
        $I->loadFixtures(SecurityFixtures::class);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost(
            '/login_check',
            [
                'username' => SecurityFixtures::REGISTERED_INACTIVE_USER_EMAIL,
                'password' => SecurityFixtures::REGISTERED_INACTIVE_USER_PASSWORD,
            ],
        );
        $I->seeResponseCodeIs(Response::HTTP_UNAUTHORIZED);
        $I->seeResponseContainsJson([
            'code' => 401,
            'message' => 'Invalid credentials.',
        ]);
    }
}
