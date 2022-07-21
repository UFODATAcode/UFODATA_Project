<?php

namespace App\Tests\Api;

use App\DataFixtures\MeasurementFixtures;
use App\Entity\RadioFrequencySpectrum;
use \App\Tests\ApiTester;
use Symfony\Component\HttpFoundation\Response;

class UpdateMeasurementCest
{
    public function iCanUpdateGivenMeasurement(ApiTester $I)
    {
        $newName = 'Second name';

        $I->loadFixtures(MeasurementFixtures::class);

        $I->seeInRepository(
            RadioFrequencySpectrum::class,
            [
                'uuid' => MeasurementFixtures::MEASUREMENT_2_UUID,
                'name' => MeasurementFixtures::MEASUREMENT_2_NAME,
            ]
        );
        $I->dontSeeInRepository(
            RadioFrequencySpectrum::class,
            [
                'uuid' => MeasurementFixtures::MEASUREMENT_2_UUID,
                'name' => $newName,
            ]
        );

        $I->setBearerTokenForUser(MeasurementFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPatch(
            '/measurements/' . MeasurementFixtures::MEASUREMENT_2_UUID,
            [
                'name' => $newName,
            ]
        );
        $I->seeResponseCodeIs(Response::HTTP_NO_CONTENT);

        $I->dontSeeInRepository(
            RadioFrequencySpectrum::class,
            [
                'uuid' => MeasurementFixtures::MEASUREMENT_2_UUID,
                'name' => MeasurementFixtures::MEASUREMENT_2_NAME,
            ]
        );
        $I->seeInRepository(
            RadioFrequencySpectrum::class,
            [
                'uuid' => MeasurementFixtures::MEASUREMENT_2_UUID,
                'name' => $newName,
            ]
        );
    }
}
