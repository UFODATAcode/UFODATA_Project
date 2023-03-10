<?php

namespace App\Tests\api;

use App\DataFixtures\UserFixtures;
use App\Tests\ApiTester;
use Symfony\Component\HttpFoundation\Response;

class ChangeUserPasswordCest
{
    public function iCanNotChangeMyOwnPasswordWhenIAmNotAuthorized(ApiTester $I): void
    {
        $I->loadFixtures(UserFixtures::class);
        $I->haveHttpHeader('Content-Type', 'application/json');

        $I->sendPost(
            '/users/' . UserFixtures::USER_1_UUID . '/change-password',
            [
                'oldPassword' => 'test',
                'newPassword' => 'New-test1',
            ]
        );

        $I->seeResponseCodeIs(Response::HTTP_UNAUTHORIZED);
    }

    public function iCanSuccessfullyChangeMyOwnPassword(ApiTester $I): void
    {
        $I->loadFixtures(UserFixtures::class);
        $I->setBearerTokenForUser(UserFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');

        $I->sendPost(
            '/users/' . UserFixtures::USER_1_UUID . '/change-password',
            [
                'oldPassword' => 'test',
                'newPassword' => 'New-test1',
            ]
        );

        $I->seeResponseCodeIs(Response::HTTP_NO_CONTENT);
    }

    public function iCanNotChangeMyOwnPasswordWhenOldPasswordIsInvalid(ApiTester $I): void
    {
        $I->loadFixtures(UserFixtures::class);
        $I->setBearerTokenForUser(UserFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');

        $I->sendPost(
            '/users/' . UserFixtures::USER_1_UUID . '/change-password',
            [
                'oldPassword' => 'invalid',
                'newPassword' => 'New-test1',
            ]
        );

        $I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
        $I->seeResponseContainsJson([
            'errors' => [
                [
                    'property' => 'oldPassword',
                    'message' => 'This value should be the user\'s current password.',
                    'code' => null,
                ]
            ]
        ]);
    }

    public function iCanNotChangeMyOwnPasswordWhenNewPasswordNotMeetsPasswordPolicy(ApiTester $I): void
    {
        $I->loadFixtures(UserFixtures::class);
        $I->setBearerTokenForUser(UserFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');

        $I->sendPost(
            '/users/' . UserFixtures::USER_1_UUID . '/change-password',
            [
                'oldPassword' => 'invalid',
                'newPassword' => 'new',
            ]
        );

        $I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
        $I->seeResponseContainsJson([
            'errors' => [
                [
                    'property' => 'newPassword',
                    'message' => 'Password must at least: be 8 characters length, contains one upper case, contains one lower case, contains one special character and contains one digit.',
                    'code' => 'a8ff120d-830b-4ea3-ac28-d192f7273e9d',
                ]
            ]
        ]);
    }
}