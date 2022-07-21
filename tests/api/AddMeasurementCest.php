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
        $measurementFileName = 'measurement-rfs.csv';
        $measurementFileCopyName = 'measurement-rfs-copy.csv';
        $measurementFileCopyPath = codecept_data_dir($measurementFileCopyName);

        copy(
            codecept_data_dir($measurementFileName),
            $measurementFileCopyPath
        );

        $I->loadFixtures(MeasurementFixtures::class);
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
                    'name' => $measurementFileCopyName,
                    'type' => 'text/csv',
                    'error' => UPLOAD_ERR_OK,
                    'size' => filesize($measurementFileCopyPath),
                    'tmp_name' => $measurementFileCopyPath,
                ],
            ]
        );
        $I->seeResponseCodeIs(Response::HTTP_NO_CONTENT);
        $I->seeInRepository(RadioFrequencySpectrum::class, ['uuid' => MeasurementFixtures::MEASUREMENT_1_UUID]);
        //assert file was saved
    }
}
