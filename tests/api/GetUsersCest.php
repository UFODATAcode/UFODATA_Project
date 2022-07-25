<?php

namespace App\Tests\Api;

use App\DataFixtures\UserFixtures;
use App\ValueObject\Pagination;
use App\Tests\ApiTester;
use Symfony\Component\HttpFoundation\Response;

class GetUsersCest
{
    public function iCanNotGetUsersWhenIAmNotNotAuthorized(ApiTester $I): void
    {
        $I->loadFixtures(UserFixtures::class);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGet('/users');
        $I->seeResponseCodeIs(Response::HTTP_UNAUTHORIZED);
    }

    public function iCanNotGetUsersWhenIAmAuthorizedButIAmNotAnAdmin(ApiTester $I): void
    {
        $I->loadFixtures(UserFixtures::class);
        $I->setBearerTokenForUser(UserFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGet('/users');
        $I->seeResponseCodeIs(Response::HTTP_FORBIDDEN);
    }

    public function iCanGetUsersWhenIAmAuthorized(ApiTester $I): void
    {
        $I->loadFixtures(UserFixtures::class);
        $I->setBearerTokenForUser(UserFixtures::ADMIN_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGet('/users');
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseContainsJson([
            'data' => [
                [
                    'uuid' => UserFixtures::ADMIN_1_UUID,
                    'name' => UserFixtures::ADMIN_1_NAME,
                    'email' => UserFixtures::ADMIN_1_EMAIL,
                    'roles' => [
                        'ROLE_ADMIN',
                    ],
                ],
                [
                    'uuid' => UserFixtures::USER_1_UUID,
                    'name' => UserFixtures::USER_1_NAME,
                    'email' => UserFixtures::USER_1_EMAIL,
                    'roles' => [
                        'ROLE_USER',
                    ],
                ],
                [
                    'uuid' => UserFixtures::USER_2_UUID,
                    'name' => UserFixtures::USER_2_NAME,
                    'email' => UserFixtures::USER_2_EMAIL,
                    'roles' => [
                        'ROLE_USER',
                    ],
                ],
                [
                    'uuid' => UserFixtures::USER_3_UUID,
                    'name' => UserFixtures::USER_3_NAME,
                    'email' => UserFixtures::USER_3_EMAIL,
                    'roles' => [
                        'ROLE_USER',
                    ],
                ],
            ],
            'pagination' => [
                'limit' => Pagination::DEFAULT_LIMIT,
                'page' => Pagination::FIRST_PAGE,
                'numberOfPages' => Pagination::DEFAULT_NUMBER_OF_PAGES,
            ],
        ]);
    }

    public function iCanLimitReturnedUsersNumberUsingLimitParameter(ApiTester $I): void
    {
        $I->loadFixtures(UserFixtures::class);
        $I->setBearerTokenForUser(UserFixtures::ADMIN_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGet('/users?limit=2');
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseContainsJson([
            'data' => [
                [
                    'uuid' => UserFixtures::ADMIN_1_UUID,
                    'name' => UserFixtures::ADMIN_1_NAME,
                    'email' => UserFixtures::ADMIN_1_EMAIL,
                    'roles' => [
                        'ROLE_ADMIN',
                    ],
                ],
                [
                    'uuid' => UserFixtures::USER_1_UUID,
                    'name' => UserFixtures::USER_1_NAME,
                    'email' => UserFixtures::USER_1_EMAIL,
                    'roles' => [
                        'ROLE_USER',
                    ],
                ],
            ],
            'pagination' => [
                'limit' => 2,
                'page' => 1,
                'numberOfPages' => 2,
            ],
        ]);
    }

    public function iCanLimitReturnedUsersNumberUsingLimitParameterAndFetchSecondPage(ApiTester $I): void
    {
        $I->loadFixtures(UserFixtures::class);
        $I->setBearerTokenForUser(UserFixtures::ADMIN_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGet('/users?limit=2&page=2');
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseContainsJson([
            'data' => [
                [
                    'uuid' => UserFixtures::USER_2_UUID,
                    'name' => UserFixtures::USER_2_NAME,
                    'email' => UserFixtures::USER_2_EMAIL,
                    'roles' => [
                        'ROLE_USER',
                    ],
                ],
                [
                    'uuid' => UserFixtures::USER_3_UUID,
                    'name' => UserFixtures::USER_3_NAME,
                    'email' => UserFixtures::USER_3_EMAIL,
                    'roles' => [
                        'ROLE_USER',
                    ],
                ],
            ],
            'pagination' => [
                'limit' => 2,
                'page' => 2,
                'numberOfPages' => 2,
            ],
        ]);
    }
}
