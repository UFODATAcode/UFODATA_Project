<?php

namespace App\Tests\Api;

use App\DataFixtures\UserFixtures;
use App\Tests\ApiTester;
use Symfony\Component\HttpFoundation\Response;

class GetUserCest
{
    public function iCanNotGetUserWhenIAmNotNotAuthorized(ApiTester $I): void
    {
        $I->loadFixtures(UserFixtures::class);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGet('/users/' . UserFixtures::USER_2_UUID);
        $I->seeResponseCodeIs(Response::HTTP_UNAUTHORIZED);
    }

    public function iCanGetUserWhenIAmAuthorized(ApiTester $I): void
    {
        $I->loadFixtures(UserFixtures::class);
        $I->setBearerTokenForUser(UserFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGet('/users/' . UserFixtures::USER_2_UUID);
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseContainsJson([
            'uuid' => UserFixtures::USER_2_UUID,
            'name' => UserFixtures::USER_2_NAME,
        ]);
    }

    public function iGetAnErrorWhenITryToGetNotExistingUser(ApiTester $I): void
    {
        $I->loadFixtures(UserFixtures::class);
        $I->setBearerTokenForUser(UserFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGet('/users/' . UserFixtures::NOT_EXISTING_USER_UUID);
        $I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
        $I->seeResponseContainsJson([
            'errors' => [
                'property' => 'uuid',
                'message' => 'A resource with "' . UserFixtures::NOT_EXISTING_USER_UUID . '" UUID does not exist.',
                'code' => 'ab1db128-e844-4dbd-9988-e0758f26a5af',
            ]
        ]);
    }
}
