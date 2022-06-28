<?php

namespace App\Tests\Api;

use App\DataFixtures\ObservationFixtures;
use App\Entity\Observation;
use \App\Tests\ApiTester;
use Symfony\Component\HttpFoundation\Response;

class UpdateObservationCest
{
    public function iCanNotUpdateObservationWhenIAmNotAuthorized(ApiTester $I)
    {
        $newNameValue = 'Completely new name';
        $oldValues = [
            'uuid' => ObservationFixtures::OBSERVATION_1_UUID,
            'name' => ObservationFixtures::OBSERVATION_1_NAME,
        ];
        $I->loadFixtures(ObservationFixtures::class);
        $I->seeInRepository(Observation::class, $oldValues);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPatch(
            '/observations/' . ObservationFixtures::OBSERVATION_1_UUID,
            [
                'name' => $newNameValue,
            ]
        );
        $I->seeResponseCodeIs(Response::HTTP_UNAUTHORIZED);
        $I->seeInRepository(Observation::class, $oldValues);
    }

    public function iCanNotUpdateObservationWhenItNotBelongsToMe(ApiTester $I)
    {
        $newNameValue = 'Completely new name';
        $I->loadFixtures(ObservationFixtures::class);
        $oldObservationValues = [
            'uuid' => ObservationFixtures::OBSERVATION_1_UUID,
            'name' => ObservationFixtures::OBSERVATION_1_NAME,
        ];
        $I->seeInRepository(Observation::class, $oldObservationValues);
        $I->setBearerTokenForUser(ObservationFixtures::USER_2_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPatch(
            '/observations/' . ObservationFixtures::OBSERVATION_1_UUID,
            [
                'name' => $newNameValue,
            ]
        );
        $I->seeResponseCodeIs(Response::HTTP_FORBIDDEN);
        $I->seeResponseContainsJson([
            'errors' => [
                [
                    'property' => 'uuid',
                    'message' => 'User is not resource owner.',
                    'code' => '4bcf7afc-662b-438c-9a0a-6822dd608b75',
                ]
            ]
        ]);
        $I->seeInRepository(Observation::class, $oldObservationValues);
    }

    public function iCanUpdateObservationWhenItBelongsToMe(ApiTester $I)
    {
        $newNameValue = 'Completely new name';
        $I->loadFixtures(ObservationFixtures::class);
        $I->seeInRepository(
            Observation::class,
            [
                'uuid' => ObservationFixtures::OBSERVATION_1_UUID,
                'name' => ObservationFixtures::OBSERVATION_1_NAME,
            ]
        );
        $I->setBearerTokenForUser(ObservationFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPatch(
            '/observations/' . ObservationFixtures::OBSERVATION_1_UUID,
            [
                'name' => $newNameValue,
            ]
        );
        $I->seeResponseCodeIs(Response::HTTP_NO_CONTENT);
        $I->seeInRepository(
            Observation::class,
            [
                'uuid' => ObservationFixtures::OBSERVATION_1_UUID,
                'name' => $newNameValue,
            ]
        );
    }

    public function iCanUpdateObservationWhenItNotBelongsToMeButIAmAnAdmin(ApiTester $I)
    {
        $newNameValue = 'Completely new name';
        $I->loadFixtures(ObservationFixtures::class);
        $I->seeInRepository(
            Observation::class,
            [
                'uuid' => ObservationFixtures::OBSERVATION_1_UUID,
                'name' => ObservationFixtures::OBSERVATION_1_NAME,
            ]
        );
        $I->setBearerTokenForUser(ObservationFixtures::ADMIN_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPatch(
            '/observations/' . ObservationFixtures::OBSERVATION_1_UUID,
            [
                'name' => $newNameValue,
            ]
        );
        $I->seeResponseCodeIs(Response::HTTP_NO_CONTENT);
        $I->seeInRepository(
            Observation::class,
            [
                'uuid' => ObservationFixtures::OBSERVATION_1_UUID,
                'name' => $newNameValue,
            ]
        );
    }

    public function iGetAnErrorWhenItryToUpdateObservationNameUsingTooLongValue(ApiTester $I)
    {
        $I->loadFixtures(ObservationFixtures::class);
        $I->setBearerTokenForUser(ObservationFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPatch(
            '/observations/' . ObservationFixtures::OBSERVATION_1_UUID,
            [
                'name' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam commodo orci ut dapibus luctus. Proin congue dolor quis feugiat auctor.',
            ]
        );
        $I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
        $I->seeResponseContainsJson([
            'errors' => [
                [
                    'property' => 'name',
                    'message' => 'This value is too long. It should have 64 characters or less.',
                    'code' => 'd94b19cc-114f-4f44-9cc4-4138e80a87b9',
                ]
            ]
        ]);
    }

    public function iGetAnErrorWhenITryToUpdateObservationNameUsingInvalidTypeValue(ApiTester $I)
    {
        $I->loadFixtures(ObservationFixtures::class);
        $I->setBearerTokenForUser(ObservationFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPatch(
            '/observations/' . ObservationFixtures::OBSERVATION_1_UUID,
            [
                'name' => 44,
            ]
        );
        $I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
        $I->seeResponseContainsJson([
            'errors' => [
                [
                    'property' => 'name',
                    'message' => 'This value should be of type string.',
                    'code' => 'ba785a8c-82cb-4283-967c-3cf342181b40',
                ]
            ]
        ]);
    }
}
