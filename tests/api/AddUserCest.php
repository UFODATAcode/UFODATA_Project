<?php

namespace App\Tests\Api;

use App\DataFixtures\UserFixtures;
use App\Entity\User;
use \App\Tests\ApiTester;
use Symfony\Component\HttpFoundation\Response;

class AddUserCest
{
    public function iCanNotAddUserIfIAmNotLoggedIn(ApiTester $I): void
    {
        $I->sendPost(
            '/users',
            [
                'uuid' => 'aeaeba0f-af29-4bae-83d0-e1d0a24fbcfe',
                'email' => 'new@user.com',
                'name' => 'nuser',
                'password' => 'strongpwd',
                'roles' => [
                    'ROLE_USER',
                ],
            ]
        );
        $I->seeResponseCodeIs(Response::HTTP_UNAUTHORIZED);
    }

    public function iCanNotAddUserIfIAmNotAnAdmin(ApiTester $I): void
    {
        $I->loadFixtures(UserFixtures::class);
        $I->setBearerTokenForUser(UserFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost(
            '/users',
            [
                'uuid' => 'aeaeba0f-af29-4bae-83d0-e1d0a24fbcfe',
                'email' => 'new@user.com',
                'name' => 'nuser',
                'password' => 'strongpwd',
                'roles' => [
                    'ROLE_USER',
                ],
            ]
        );
        $I->seeResponseCodeIs(Response::HTTP_FORBIDDEN);
    }

    public function iCanAddUserIfIAmAnAdmin(ApiTester $I): void
    {
        $newUserUuid = 'aeaeba0f-af29-4bae-83d0-e1d0a24fbcfe';

        $I->loadFixtures(UserFixtures::class);
        $I->dontSeeInRepository(User::class, ['uuid' => $newUserUuid]);

        $I->setBearerTokenForUser(UserFixtures::ADMIN_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost(
            '/users',
            [
                'uuid' => $newUserUuid,
                'email' => 'new@user.com',
                'name' => 'nuser',
                'password' => 'strongpwd',
                'roles' => [
                    'ROLE_USER',
                ],
            ]
        );

        $I->seeResponseCodeIs(Response::HTTP_NO_CONTENT);
        $I->seeInRepository(User::class, ['uuid' => $newUserUuid]);
    }

    public function iGetAnErrorWhenIDontProvideInvalidUuid(ApiTester $I): void
    {
        $I->loadFixtures(UserFixtures::class);

        $I->setBearerTokenForUser(UserFixtures::ADMIN_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost(
            '/users',
            [
                'uuid' => 'some-invalid-uuid',
                'email' => 'new@user.com',
                'name' => 'nuser',
                'password' => 'strongpwd',
                'roles' => [
                    'ROLE_USER',
                ],
            ]
        );

        $I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
        $I->seeResponseContainsJson([
            'errors' => [
                [
                    'property' => 'uuid',
                    'message' => 'This is not a valid UUID.',
                    'code' => '51120b12-a2bc-41bf-aa53-cd73daf330d0',
                ]
            ]
        ]);
    }

    public function iGetAnErrorWhenIDontProvideUuid(ApiTester $I): void
    {
        $I->loadFixtures(UserFixtures::class);

        $I->setBearerTokenForUser(UserFixtures::ADMIN_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost(
            '/users',
            [
                'email' => 'new@user.com',
                'name' => 'nuser',
                'password' => 'strongpwd',
                'roles' => [
                    'ROLE_USER',
                ],
            ]
        );

        $I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
        $I->seeResponseContainsJson([
            'errors' => [
                [
                    'property' => 'uuid',
                    'message' => 'This value should not be blank.',
                    'code' => 'c1051bb4-d103-4f74-8988-acbcafc7fdc3',
                ]
            ]
        ]);
    }

    public function iGetAnErrorWhenIUseAlreadyExistingUuid(ApiTester $I): void
    {
        $I->loadFixtures(UserFixtures::class);

        $I->setBearerTokenForUser(UserFixtures::ADMIN_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost(
            '/users',
            [
                'uuid' => UserFixtures::USER_1_UUID,
                'email' => 'new@user.com',
                'name' => 'nuser',
                'password' => 'strongpwd',
                'roles' => [
                    'ROLE_USER',
                ],
            ]
        );

        $I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
        $I->seeResponseContainsJson([
            'errors' => [
                [
                    'property' => 'uuid',
                    'message' => 'A resource with "' . UserFixtures::USER_1_UUID . '" UUID already exists.',
                    'code' => '74ae47e1-6d43-4dfc-831e-7db274ff494b',
                ]
            ]
        ]);
    }

    public function iGetAnErrorWhenIDontSendAnEmail(ApiTester $I): void
    {
        $I->loadFixtures(UserFixtures::class);

        $I->setBearerTokenForUser(UserFixtures::ADMIN_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost(
            '/users',
            [
                'uuid' => 'aeaeba0f-af29-4bae-83d0-e1d0a24fbcfe',
                'name' => 'nuser',
                'password' => 'strongpwd',
                'roles' => [
                    'ROLE_USER',
                ],
            ]
        );

        $I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
        $I->seeResponseContainsJson([
            'errors' => [
                [
                    'property' => 'email',
                    'message' => 'This value should not be blank.',
                    'code' => 'c1051bb4-d103-4f74-8988-acbcafc7fdc3',
                ]
            ]
        ]);
    }

    public function iGetAnErrorWhenISendInvalidEmail(ApiTester $I): void
    {
        $I->loadFixtures(UserFixtures::class);

        $I->setBearerTokenForUser(UserFixtures::ADMIN_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost(
            '/users',
            [
                'uuid' => 'aeaeba0f-af29-4bae-83d0-e1d0a24fbcfe',
                'email' => 'some@invalid@email:net',
                'name' => 'nuser',
                'password' => 'strongpwd',
                'roles' => [
                    'ROLE_USER',
                ],
            ]
        );

        $I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
        $I->seeResponseContainsJson([
            'errors' => [
                [
                    'property' => 'email',
                    'message' => 'This value is not a valid email address.',
                    'code' => 'bd79c0ab-ddba-46cc-a703-a7a4b08de310',
                ]
            ]
        ]);
    }

    public function iGetAnErrorWhenISendInvalidPassword(ApiTester $I): void
    {
        $I->loadFixtures(UserFixtures::class);

        $I->setBearerTokenForUser(UserFixtures::ADMIN_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost(
            '/users',
            [
                'uuid' => 'aeaeba0f-af29-4bae-83d0-e1d0a24fbcfe',
                'email' => 'some@user.com',
                'name' => 'nuser',
                'password' => 1,
                'roles' => [
                    'ROLE_USER',
                ],
            ]
        );

        $I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
        $I->seeResponseContainsJson([
            'errors' => [
                [
                    'property' => 'password',
                    'message' => 'This value should be of type string.',
                    'code' => 'ba785a8c-82cb-4283-967c-3cf342181b40',
                ]
            ]
        ]);
    }

    public function iGetAnErrorWhenIDontSendAPassword(ApiTester $I): void
    {
        $I->loadFixtures(UserFixtures::class);

        $I->setBearerTokenForUser(UserFixtures::ADMIN_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost(
            '/users',
            [
                'uuid' => 'aeaeba0f-af29-4bae-83d0-e1d0a24fbcfe',
                'email' => 'some@user.com',
                'name' => 'nuser',
                'roles' => [
                    'ROLE_USER',
                ],
            ]
        );

        $I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
        $I->seeResponseContainsJson([
            'errors' => [
                [
                    'property' => 'password',
                    'message' => 'This value should not be blank.',
                    'code' => 'c1051bb4-d103-4f74-8988-acbcafc7fdc3',
                ]
            ]
        ]);
    }

    public function iGetAnErrorWhenISendInvalidRoleValue(ApiTester $I): void
    {
        $I->loadFixtures(UserFixtures::class);

        $I->setBearerTokenForUser(UserFixtures::ADMIN_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost(
            '/users',
            [
                'uuid' => 'aeaeba0f-af29-4bae-83d0-e1d0a24fbcfe',
                'email' => 'some@user.com',
                'name' => 'nuser',
                'password' => 'strongpwd',
                'roles' => [
                    'NOT_EXISTIN_ROLE',
                ],
            ]
        );

        $I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
        $I->seeResponseContainsJson([
            'errors' => [
                [
                    'property' => 'roles[0]',
                    'message' => 'The value you selected is not a valid choice.',
                    'code' => '8e179f1b-97aa-4560-a02f-2a8b42e49df7',
                ]
            ]
        ]);
    }
}
