<?php

namespace App\Tests\Api;

use App\DataFixtures\ObservationFixtures;
use App\Entity\Observation;
use App\Tests\ApiTester;
use Symfony\Component\HttpFoundation\Response;

class AddObservationCest
{
    public function canNotAddObservationWhenNotAuthorized(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        try {
            $I->sendPost('/observations', [
                'uuid' => '9ef30757-8f8e-4473-b326-b2ee487aefee',
                'name' => 'UAP at my place',
            ]);
        } catch (\Throwable $e) {}
        $I->seeResponseCodeIs(Response::HTTP_UNAUTHORIZED);
    }

    public function canAddObservationWhenAuthorized(ApiTester $I): void
    {
        $I->loadFixtures(ObservationFixtures::class);
        $I->setBearerTokenForUser(ObservationFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/observations', [
            'uuid' => '9ef30757-8f8e-4473-b326-b2ee487aefee',
            'name' => 'UAP at my place',
        ]);
        $I->seeResponseCodeIs(Response::HTTP_NO_CONTENT);
        $I->seeInRepository(Observation::class, [
            'uuid' => '9ef30757-8f8e-4473-b326-b2ee487aefee',
            'name' => 'UAP at my place',
        ]);
    }

    public function getErrorWhenTryToCreateObservationWithExistingUuidProvided(ApiTester $I): void
    {
        $I->loadFixtures(ObservationFixtures::class);
        $I->setBearerTokenForUser(ObservationFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/observations', [
            'uuid' => ObservationFixtures::OBSERVATION_1_UUID,
            'name' => 'UAP at my place 1',
        ]);
        $I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
        $I->seeResponseContains('A resource with "' . ObservationFixtures::OBSERVATION_1_UUID . '" UUID already exists.');
    }

    public function getErrorWhenTryToCreateObservationWithInvalidUuidProvided(ApiTester $I): void
    {
        $I->loadFixtures(ObservationFixtures::class);
        $I->setBearerTokenForUser(ObservationFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/observations', [
            'uuid' => 'some-invalid-uuid-pseudo-identifier',
            'name' => 'Lorem ipsum',
        ]);
        $I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
        $I->seeResponseContains('This is not a valid UUID.');
    }

    public function getErrorWhenTryToCreateObservationWithoutUuidProvided(ApiTester $I): void
    {
        $I->loadFixtures(ObservationFixtures::class);
        $I->setBearerTokenForUser(ObservationFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/observations', [
            'name' => 'Lorem ipsum',
        ]);
        $I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
        $I->seeResponseContains('This value should not be null.');
    }

    public function getErrorWhenTryToCreateObservationWithEmptyUuidProvided(ApiTester $I): void
    {
        $I->loadFixtures(ObservationFixtures::class);
        $I->setBearerTokenForUser(ObservationFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/observations', [
            'uuid' => '',
            'name' => 'Lorem ipsum',
        ]);
        $I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
        $I->seeResponseContains('This value should not be blank.');
    }

    public function getErrorWhenTryToCreateObservationWithoutNameProvided(ApiTester $I): void
    {
        $I->loadFixtures(ObservationFixtures::class);
        $I->setBearerTokenForUser(ObservationFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/observations', [
            'uuid' => '9ef30757-8f8e-4473-b326-b2ee487aefee',
        ]);
        $I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
        $I->seeResponseContains('This value should not be null.');
    }

    public function getErrorWhenTryToCreateObservationWithEmptyNameProvided(ApiTester $I): void
    {
        $I->loadFixtures(ObservationFixtures::class);
        $I->setBearerTokenForUser(ObservationFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/observations', [
            'uuid' => '9ef30757-8f8e-4473-b326-b2ee487aefee',
            'name' => '',
        ]);
        $I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
        $I->seeResponseContains('This value is too short. It should have 1 character or more.');
    }

    public function getErrorWhenTryToCreateObservationWithTooLongNameProvided(ApiTester $I): void
    {
        $I->loadFixtures(ObservationFixtures::class);
        $I->setBearerTokenForUser(ObservationFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/observations', [
            'uuid' => '9ef30757-8f8e-4473-b326-b2ee487aefee',
            'name' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam commodo orci ut dapibus luctus. Proin congue dolor quis feugiat auctor.',
        ]);
        $I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
        $I->seeResponseContains('This value is too long. It should have 64 characters or less.');
    }

    public function getErrorWhenTryToCreateObservationWithInvalidNameTypeValueProvided(ApiTester $I): void
    {
        $I->loadFixtures(ObservationFixtures::class);
        $I->setBearerTokenForUser(ObservationFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/observations', [
            'uuid' => '9ef30757-8f8e-4473-b326-b2ee487aefee',
            'name' => 44,
        ]);
        $I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
        $I->seeResponseContains('This value should be of type string.');
    }
}
