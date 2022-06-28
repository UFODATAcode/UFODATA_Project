<?php

namespace App\Tests\Api;

use App\DataFixtures\ObservationFixtures;
use App\Entity\Observation;
use App\Tests\ApiTester;
use Symfony\Component\HttpFoundation\Response;

class AddObservationCest
{
    public function iCanNotAddObservationWhenIAmNotAuthorized(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/observations', [
            'uuid' => '9ef30757-8f8e-4473-b326-b2ee487aefee',
            'name' => 'UAP at my place',
        ]);
        $I->seeResponseCodeIs(Response::HTTP_UNAUTHORIZED);
    }

    public function iCanAddObservationWhenIAmAuthorized(ApiTester $I): void
    {
        $newObservationData = [
            'uuid' => '9ef30757-8f8e-4473-b326-b2ee487aefee',
            'name' => 'UAP at my place',
        ];
        $I->loadFixtures(ObservationFixtures::class);
        $I->dontSeeInRepository(Observation::class, $newObservationData);
        $I->setBearerTokenForUser(ObservationFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/observations', $newObservationData);
        $I->seeResponseCodeIs(Response::HTTP_NO_CONTENT);
        $I->seeInRepository(Observation::class, $newObservationData);
    }

    public function iGetAnErrorWhenITryToCreateObservationWithAlreadyExistingUuid(ApiTester $I): void
    {
        $I->loadFixtures(ObservationFixtures::class);
        $I->setBearerTokenForUser(ObservationFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/observations', [
            'uuid' => ObservationFixtures::OBSERVATION_1_UUID,
            'name' => 'UAP at my place 1',
        ]);
        $I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
        $I->seeResponseContainsJson([
            'errors' => [
                [
                    'property' => 'uuid',
                    'message' => 'A resource with "' . ObservationFixtures::OBSERVATION_1_UUID . '" UUID already exists.',
                    'code' => '74ae47e1-6d43-4dfc-831e-7db274ff494b',
                ]
            ]
        ]);
    }

    public function iGetAnErrorWhenITryToCreateObservationWithInvalidUuid(ApiTester $I): void
    {
        $I->loadFixtures(ObservationFixtures::class);
        $I->setBearerTokenForUser(ObservationFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/observations', [
            'uuid' => 'some-invalid-uuid-pseudo-identifier',
            'name' => 'Lorem ipsum',
        ]);
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

    public function iGetAnErrorWhenITryToCreateObservationWithoutUuid(ApiTester $I): void
    {
        $I->loadFixtures(ObservationFixtures::class);
        $I->setBearerTokenForUser(ObservationFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/observations', [
            'name' => 'Lorem ipsum',
        ]);
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

    public function iGetAnErrorWhenITryToCreateObservationWithEmptyUuid(ApiTester $I): void
    {
        $I->loadFixtures(ObservationFixtures::class);
        $I->setBearerTokenForUser(ObservationFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/observations', [
            'uuid' => '',
            'name' => 'Lorem ipsum',
        ]);
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

    public function iGetAnErrorWhenITryToCreateObservationWithoutName(ApiTester $I): void
    {
        $I->loadFixtures(ObservationFixtures::class);
        $I->setBearerTokenForUser(ObservationFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/observations', [
            'uuid' => '9ef30757-8f8e-4473-b326-b2ee487aefee',
        ]);
        $I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
        $I->seeResponseContainsJson([
            'errors' => [
                [
                    'property' => 'name',
                    'message' => 'This value should not be null.',
                    'code' => 'ad32d13f-c3d4-423b-909a-857b961eb720',
                ]
            ]
        ]);
    }

    public function iGetAnErrorWhenITryToCreateObservationWithEmptyName(ApiTester $I): void
    {
        $I->loadFixtures(ObservationFixtures::class);
        $I->setBearerTokenForUser(ObservationFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/observations', [
            'uuid' => '9ef30757-8f8e-4473-b326-b2ee487aefee',
            'name' => '',
        ]);
        $I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
        $I->seeResponseContainsJson([
            'errors' => [
                [
                    'property' => 'name',
                    'message' => 'This value is too short. It should have 1 character or more.',
                    'code' => '9ff3fdc4-b214-49db-8718-39c315e33d45',
                ]
            ]
        ]);
    }

    public function iGetAnErrorWhenITryToCreateObservationWithTooLongName(ApiTester $I): void
    {
        $I->loadFixtures(ObservationFixtures::class);
        $I->setBearerTokenForUser(ObservationFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/observations', [
            'uuid' => '9ef30757-8f8e-4473-b326-b2ee487aefee',
            'name' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam commodo orci ut dapibus luctus. Proin congue dolor quis feugiat auctor.',
        ]);
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

    public function iGetAnErrorWhenITryToCreateObservationWithInvalidNameTypeValue(ApiTester $I): void
    {
        $I->loadFixtures(ObservationFixtures::class);
        $I->setBearerTokenForUser(ObservationFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/observations', [
            'uuid' => '9ef30757-8f8e-4473-b326-b2ee487aefee',
            'name' => 44,
        ]);
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
