<?php

namespace App\Tests\Api;

use App\DataFixtures\MeasurementFixtures;
use App\Entity\RadioFrequencySpectrum;
use App\Enum\MeasurementType;
use App\Tests\ApiTester;
use Symfony\Component\HttpFoundation\Response;

class AddMeasurementCest
{
    public function iCanAddRadioFrequencyMeasurement(ApiTester $I)
    {
        $I->loadFixtures(MeasurementFixtures::class);
//        $I->dontSeeInCollection(\App\Document\Measurement\RadioFrequencySpectrum::COLLECTION_NAME, ['uuid' => MeasurementFixtures::MEASUREMENT_1_UUID]);
        $I->dontSeeInRepository(RadioFrequencySpectrum::class, ['uuid' => MeasurementFixtures::MEASUREMENT_1_UUID]);
        $I->setBearerTokenForUser(MeasurementFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost(
            '/measurements',
            [
                'uuid' => MeasurementFixtures::MEASUREMENT_1_UUID,
                'observationUuid' => MeasurementFixtures::OBSERVATION_1_UUID,
                'measurementType' => MeasurementType::RadioFrequencySpectrum->value, //create an enum for measurement types
            ],
            [
                'measurement' => [
                    'name' => 'measurement-rfs.csv',
                    'type' => 'text/csv',
                    'error' => UPLOAD_ERR_OK,
                    'size' => filesize(codecept_data_dir('measurement-rfs.csv')),
                    'tmp_name' => codecept_data_dir('measurement-rfs.csv'),
                ],
            ]
        );
        $I->seeResponseCodeIs(Response::HTTP_NO_CONTENT);
        $I->seeInRepository(RadioFrequencySpectrum::class, ['uuid' => MeasurementFixtures::MEASUREMENT_1_UUID]);
        //assert file was saved
//        $I->seeInCollection(\App\Document\Measurement\RadioFrequencySpectrum::COLLECTION_NAME, ['uuid' => MeasurementFixtures::MEASUREMENT_1_UUID]);
    }
}
