<?php

namespace App\Tests\Api;

use App\DataFixtures\MeasurementFixtures;
use App\Entity\Measurement;
use \App\Tests\ApiTester;
use Symfony\Component\HttpFoundation\Response;

class DeleteMeasurementCest
{
    public function iCanNotDeleteMeasurementWhenIAmNotNotAuthorized(ApiTester $I)
    {
        $I->loadFixtures(MeasurementFixtures::class);
        $I->seeInRepository(Measurement::class, ['uuid' => MeasurementFixtures::MEASUREMENT_1_UUID]);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendDelete('/measurements/' . MeasurementFixtures::MEASUREMENT_1_UUID);
        $I->seeResponseCodeIs(Response::HTTP_UNAUTHORIZED);
        $I->seeResponseContainsJson([
            'code' => 401,
            'message' => 'JWT Token not found',
        ]);
        $I->seeInRepository(Measurement::class, ['uuid' => MeasurementFixtures::MEASUREMENT_1_UUID]);
    }

    public function iCanNotDeleteMeasurementIfItNotBelongsToMe(ApiTester $I)
    {
        $I->loadFixtures(MeasurementFixtures::class);
        $I->seeInRepository(Measurement::class, ['uuid' => MeasurementFixtures::MEASUREMENT_1_UUID]);
        $I->setBearerTokenForUser(MeasurementFixtures::USER_2_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendDelete('/measurements/' . MeasurementFixtures::MEASUREMENT_1_UUID);
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
        $I->seeInRepository(Measurement::class, ['uuid' => MeasurementFixtures::MEASUREMENT_1_UUID]);
    }

    public function iCanDeleteMeasurementWhenItBelongsToMe(ApiTester $I)
    {
        $I->loadFixtures(MeasurementFixtures::class);
        $I->seeInRepository(Measurement::class, ['uuid' => MeasurementFixtures::MEASUREMENT_1_UUID]);
        $I->setBearerTokenForUser(MeasurementFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendDelete('/measurements/' . MeasurementFixtures::MEASUREMENT_1_UUID);
        $I->dontSeeInRepository(Measurement::class, ['uuid' => MeasurementFixtures::MEASUREMENT_1_UUID]);
    }

    public function iCanDeleteMeasurementWhenIAmAnAdminAndNotItsOwner(ApiTester $I)
    {
        $I->loadFixtures(MeasurementFixtures::class);
        $I->seeInRepository(Measurement::class, ['uuid' => MeasurementFixtures::MEASUREMENT_1_UUID]);
        $I->setBearerTokenForUser(MeasurementFixtures::ADMIN_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendDelete('/measurements/' . MeasurementFixtures::MEASUREMENT_1_UUID);
        $I->dontSeeInRepository(Measurement::class, ['uuid' => MeasurementFixtures::MEASUREMENT_1_UUID]);
    }

    public function iGetAnErrorWhenITryToDeleteMeasurementThatDoesNotExist(ApiTester $I)
    {
        $I->loadFixtures(MeasurementFixtures::class);
        $I->dontSeeInRepository(Measurement::class, ['uuid' => MeasurementFixtures::NOT_EXISTING_MEASUREMENT_UUID]);
        $I->setBearerTokenForUser(MeasurementFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendDelete('/measurements/' . MeasurementFixtures::NOT_EXISTING_MEASUREMENT_UUID);
        $I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
        $I->seeResponseContainsJson([
            'errors' => [
                [
                    'property' => 'uuid',
                    'message' => 'A resource with "' . MeasurementFixtures::NOT_EXISTING_MEASUREMENT_UUID . '" UUID does not exist.',
                    'code' => 'ab1db128-e844-4dbd-9988-e0758f26a5af',
                ]
            ]
        ]);
    }
}
