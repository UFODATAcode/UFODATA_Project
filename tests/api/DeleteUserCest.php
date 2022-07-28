<?php

namespace App\Tests\Api;

use App\DataFixtures\UserFixtures;
use App\Entity\DeletedResource;
use App\Entity\User;
use \App\Tests\ApiTester;
use Symfony\Component\HttpFoundation\Response;

class DeleteUserCest
{
    public function iCanNotDeleteUserWhenIAmNotNotAuthorized(ApiTester $I)
    {
        $I->loadFixtures(UserFixtures::class);
        $I->seeInRepository(User::class, ['uuid' => UserFixtures::USER_1_UUID]);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendDelete('/users/' . UserFixtures::USER_1_UUID);
        $I->seeResponseCodeIs(Response::HTTP_UNAUTHORIZED);
        $I->seeResponseContainsJson([
            'code' => 401,
            'message' => 'JWT Token not found',
        ]);
        $I->seeInRepository(User::class, ['uuid' => UserFixtures::USER_1_UUID]);
    }

    public function iCanNotDeleteUserIfIAmNotAnAdmin(ApiTester $I)
    {
        $I->loadFixtures(UserFixtures::class);
        $I->seeInRepository(User::class, ['uuid' => UserFixtures::USER_1_UUID]);
        $I->setBearerTokenForUser(UserFixtures::USER_2_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendDelete('/users/' . UserFixtures::USER_1_UUID);
        $I->seeResponseCodeIs(Response::HTTP_FORBIDDEN);
        $I->seeInRepository(User::class, ['uuid' => UserFixtures::USER_1_UUID]);
    }

    public function iCanDeleteUserWhenIAmAnAdmin(ApiTester $I)
    {
        $I->loadFixtures(UserFixtures::class);
        $I->seeInRepository(User::class, ['uuid' => UserFixtures::USER_1_UUID]);
        $I->dontSeeInRepository(DeletedResource::class, ['uuid' => UserFixtures::USER_1_UUID]);
        $I->setBearerTokenForUser(UserFixtures::ADMIN_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendDelete('/users/' . UserFixtures::USER_1_UUID);
        $I->seeResponseCodeIs(Response::HTTP_NO_CONTENT);
        $I->dontSeeInRepository(User::class, ['uuid' => UserFixtures::USER_1_UUID]);
        $I->seeInRepository(DeletedResource::class, ['uuid' => UserFixtures::USER_1_UUID]);
    }

    public function iGetAnErrorWhenITryToDeleteUserThatDoesNotExist(ApiTester $I)
    {
        $I->loadFixtures(UserFixtures::class);
        $I->dontSeeInRepository(User::class, ['uuid' => UserFixtures::NOT_EXISTING_USER_UUID]);
        $I->setBearerTokenForUser(UserFixtures::ADMIN_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendDelete('/users/' . UserFixtures::NOT_EXISTING_USER_UUID);
        $I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
        $I->seeResponseContainsJson([
            'errors' => [
                [
                    'property' => 'uuid',
                    'message' => 'A resource with "' . UserFixtures::NOT_EXISTING_USER_UUID . '" UUID does not exist.',
                    'code' => 'ab1db128-e844-4dbd-9988-e0758f26a5af',
                ]
            ]
        ]);
    }
}
