<?php

namespace App\Tests\Api;

use App\DataFixtures\ObservationFixtures;
use App\Entity\DeletedResource;
use App\Entity\Observation;
use \App\Tests\ApiTester;
use Symfony\Component\HttpFoundation\Response;

class DeleteObservationCest
{
    public function iCanNotDeleteObservationWhenIAmNotNotAuthorized(ApiTester $I)
    {
        $I->loadFixtures(ObservationFixtures::class);
        $I->seeInRepository(Observation::class, ['uuid' => ObservationFixtures::OBSERVATION_1_UUID]);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendDelete('/observations/' . ObservationFixtures::OBSERVATION_1_UUID);
        $I->seeResponseCodeIs(Response::HTTP_UNAUTHORIZED);
        $I->seeResponseContainsJson([
            'code' => 401,
            'message' => 'JWT Token not found',
        ]);
        $I->seeInRepository(Observation::class, ['uuid' => ObservationFixtures::OBSERVATION_1_UUID]);
    }

    public function iCanNotDeleteObservationIfItNotBelongsToMe(ApiTester $I)
    {
        $I->loadFixtures(ObservationFixtures::class);
        $I->seeInRepository(Observation::class, ['uuid' => ObservationFixtures::OBSERVATION_1_UUID]);
        $I->setBearerTokenForUser(ObservationFixtures::USER_2_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendDelete('/observations/' . ObservationFixtures::OBSERVATION_1_UUID);
        $I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
        $I->seeResponseContainsJson([
            'errors' => [
                [
                    'property' => 'uuid',
                    'message' => 'User is not resource owner.',
                    'code' => '4bcf7afc-662b-438c-9a0a-6822dd608b75',
                ]
            ]
        ]);
        $I->seeInRepository(Observation::class, ['uuid' => ObservationFixtures::OBSERVATION_1_UUID]);
    }

    public function iCanDeleteObservationWhenItBelongsToMe(ApiTester $I)
    {
        $I->loadFixtures(ObservationFixtures::class);
        $I->seeInRepository(Observation::class, ['uuid' => ObservationFixtures::OBSERVATION_1_UUID]);
        $I->dontSeeInRepository(DeletedResource::class, ['uuid' => ObservationFixtures::OBSERVATION_1_UUID]);
        $I->setBearerTokenForUser(ObservationFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendDelete('/observations/' . ObservationFixtures::OBSERVATION_1_UUID);
        $I->dontSeeInRepository(Observation::class, ['uuid' => ObservationFixtures::OBSERVATION_1_UUID]);
        $I->seeInRepository(DeletedResource::class, ['uuid' => ObservationFixtures::OBSERVATION_1_UUID]);
    }

    public function iCanDeleteObservationWhenIAmAnAdminAndNotItsOwner(ApiTester $I)
    {
        $I->loadFixtures(ObservationFixtures::class);
        $I->seeInRepository(Observation::class, ['uuid' => ObservationFixtures::OBSERVATION_1_UUID]);
        $I->setBearerTokenForUser(ObservationFixtures::ADMIN_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendDelete('/observations/' . ObservationFixtures::OBSERVATION_1_UUID);
        $I->dontSeeInRepository(Observation::class, ['uuid' => ObservationFixtures::OBSERVATION_1_UUID]);
    }

    public function iGetAnErrorWhenITryToDeleteObservationThatDoesNotExist(ApiTester $I)
    {
        $I->loadFixtures(ObservationFixtures::class);
        $I->dontSeeInRepository(Observation::class, ['uuid' => ObservationFixtures::NOT_EXISTING_OBSERVATION_UUID]);
        $I->setBearerTokenForUser(ObservationFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendDelete('/observations/' . ObservationFixtures::NOT_EXISTING_OBSERVATION_UUID);
        $I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
        $I->seeResponseContainsJson([
            'errors' => [
                [
                    'property' => 'uuid',
                    'message' => 'A resource with "' . ObservationFixtures::NOT_EXISTING_OBSERVATION_UUID . '" UUID does not exist.',
                    'code' => 'ab1db128-e844-4dbd-9988-e0758f26a5af',
                ]
            ]
        ]);
    }
}
