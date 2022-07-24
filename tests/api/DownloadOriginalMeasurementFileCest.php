<?php

namespace App\Tests\Api;

use App\DataFixtures\MeasurementFixtures;
use \App\Tests\ApiTester;
use Symfony\Component\HttpFoundation\Response;

class DownloadOriginalMeasurementFileCest
{
    public function iCanNotDownloadOriginalMeasurementFileIfIAmNotLoggedIn(ApiTester $I): void
    {
        $I->loadFixtures(MeasurementFixtures::class);
        $I->sendGet('/measurements/' . MeasurementFixtures::MEASUREMENT_1_UUID . '/download-original');
        $I->seeResponseCodeIs(Response::HTTP_UNAUTHORIZED);
    }

    public function iGetAnErrorWhenITryToDownloadOriginalMeasurementFileUsingNotExistingUuid(ApiTester $I): void
    {
        $I->loadFixtures(MeasurementFixtures::class);
        $I->setBearerTokenForUser(MeasurementFixtures::USER_1_EMAIL);
        $I->sendGet('/measurements/' . MeasurementFixtures::NOT_EXISTING_MEASUREMENT_UUID . '/download-original');
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

    public function iCanDownloadOriginalMeasurementFileWhenIAmLoggedInAndUsingExistingUuid(ApiTester $I): void
    {
        $I->loadFixtures(MeasurementFixtures::class);
        $I->setBearerTokenForUser(MeasurementFixtures::USER_1_EMAIL);
        $I->sendGet('/measurements/' . MeasurementFixtures::MEASUREMENT_1_UUID . '/download-original');
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeHttpHeader('Content-Disposition', 'attachment; filename=test-measurement-rfs.csv');
    }
}
