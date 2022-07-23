<?php

namespace App\Tests\Api;

use App\DataFixtures\MeasurementFixtures;
use App\Enum\MeasurementType;
use App\ValueObject\Pagination;
use App\Tests\ApiTester;
use Symfony\Component\HttpFoundation\Response;

class GetMeasurementsCest
{
    public function iCanNotGetMeasurementsWhenIAmNotNotAuthorized(ApiTester $I): void
    {
        $I->loadFixtures(MeasurementFixtures::class);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGet('/measurements');
        $I->seeResponseCodeIs(Response::HTTP_UNAUTHORIZED);
    }

    public function iCanGetMeasurementsWhenIAmAuthorized(ApiTester $I): void
    {
        $I->loadFixtures(MeasurementFixtures::class);
        $I->setBearerTokenForUser(MeasurementFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGet('/measurements');
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseContainsJson([
            'data' => [
                [
                    'uuid' => MeasurementFixtures::MEASUREMENT_1_UUID,
                    'name' => MeasurementFixtures::MEASUREMENT_1_NAME,
                    'measurementType' => MeasurementType::RadioFrequencySpectrum->value,
                    'observation' => [
                        'uuid' => MeasurementFixtures::OBSERVATION_1_UUID,
                        'name' => MeasurementFixtures::OBSERVATION_1_NAME,
                    ],
                    'provider' => [
                        'uuid' => MeasurementFixtures::USER_1_UUID,
                        'name' => MeasurementFixtures::USER_1_NAME,
                    ],
                ],
                [
                    'uuid' => MeasurementFixtures::MEASUREMENT_2_UUID,
                    'name' => MeasurementFixtures::MEASUREMENT_2_NAME,
                    'measurementType' => MeasurementType::MissionControlData->value,
                    'observation' => [
                        'uuid' => MeasurementFixtures::OBSERVATION_1_UUID,
                        'name' => MeasurementFixtures::OBSERVATION_1_NAME,
                    ],
                    'provider' => [
                        'uuid' => MeasurementFixtures::USER_1_UUID,
                        'name' => MeasurementFixtures::USER_1_NAME,
                    ],
                ],
                [
                    'uuid' => MeasurementFixtures::MEASUREMENT_3_UUID,
                    'name' => MeasurementFixtures::MEASUREMENT_3_NAME,
                    'measurementType' => MeasurementType::MissionControlAdsBFlightTracking->value,
                    'observation' => [
                        'uuid' => MeasurementFixtures::OBSERVATION_2_UUID,
                        'name' => MeasurementFixtures::OBSERVATION_2_NAME,
                    ],
                    'provider' => [
                        'uuid' => MeasurementFixtures::USER_2_UUID,
                        'name' => MeasurementFixtures::USER_2_NAME,
                    ],
                ],
                [
                    'uuid' => MeasurementFixtures::MEASUREMENT_4_UUID,
                    'name' => MeasurementFixtures::MEASUREMENT_4_NAME,
                    'measurementType' => MeasurementType::MissionControlWeather->value,
                    'observation' => [
                        'uuid' => MeasurementFixtures::OBSERVATION_2_UUID,
                        'name' => MeasurementFixtures::OBSERVATION_2_NAME,
                    ],
                    'provider' => [
                        'uuid' => MeasurementFixtures::USER_2_UUID,
                        'name' => MeasurementFixtures::USER_2_NAME,
                    ],
                ],
            ],
            'pagination' => [
                'limit' => Pagination::DEFAULT_LIMIT,
                'page' => Pagination::FIRST_PAGE,
                'numberOfPages' => Pagination::DEFAULT_NUMBER_OF_PAGES,
            ],
        ]);
    }

    public function iCanLimitReturnedMeasurementsNumberUsingLimitParameter(ApiTester $I): void
    {
        $I->loadFixtures(MeasurementFixtures::class);
        $I->setBearerTokenForUser(MeasurementFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGet('/measurements?limit=2');
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseContainsJson([
            'data' => [
                [
                    'uuid' => MeasurementFixtures::MEASUREMENT_1_UUID,
                    'name' => MeasurementFixtures::MEASUREMENT_1_NAME,
                    'measurementType' => MeasurementType::RadioFrequencySpectrum->value,
                    'observation' => [
                        'uuid' => MeasurementFixtures::OBSERVATION_1_UUID,
                        'name' => MeasurementFixtures::OBSERVATION_1_NAME,
                    ],
                    'provider' => [
                        'uuid' => MeasurementFixtures::USER_1_UUID,
                        'name' => MeasurementFixtures::USER_1_NAME,
                    ],
                ],
                [
                    'uuid' => MeasurementFixtures::MEASUREMENT_2_UUID,
                    'name' => MeasurementFixtures::MEASUREMENT_2_NAME,
                    'measurementType' => MeasurementType::MissionControlData->value,
                    'observation' => [
                        'uuid' => MeasurementFixtures::OBSERVATION_1_UUID,
                        'name' => MeasurementFixtures::OBSERVATION_1_NAME,
                    ],
                    'provider' => [
                        'uuid' => MeasurementFixtures::USER_1_UUID,
                        'name' => MeasurementFixtures::USER_1_NAME,
                    ],
                ],
            ],
            'pagination' => [
                'limit' => 2,
                'page' => 1,
                'numberOfPages' => 2,
            ],
        ]);
    }

    public function iCanLimitReturnedMeasurementsNumberUsingLimitParameterAndFetchSecondPage(ApiTester $I): void
    {
        $I->loadFixtures(MeasurementFixtures::class);
        $I->setBearerTokenForUser(MeasurementFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGet('/measurements?limit=2&page=2');
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseContainsJson([
            'data' => [
                [
                    'uuid' => MeasurementFixtures::MEASUREMENT_3_UUID,
                    'name' => MeasurementFixtures::MEASUREMENT_3_NAME,
                    'measurementType' => MeasurementType::MissionControlAdsBFlightTracking->value,
                    'observation' => [
                        'uuid' => MeasurementFixtures::OBSERVATION_2_UUID,
                        'name' => MeasurementFixtures::OBSERVATION_2_NAME,
                    ],
                    'provider' => [
                        'uuid' => MeasurementFixtures::USER_2_UUID,
                        'name' => MeasurementFixtures::USER_2_NAME,
                    ],
                ],
                [
                    'uuid' => MeasurementFixtures::MEASUREMENT_4_UUID,
                    'name' => MeasurementFixtures::MEASUREMENT_4_NAME,
                    'measurementType' => MeasurementType::MissionControlWeather->value,
                    'observation' => [
                        'uuid' => MeasurementFixtures::OBSERVATION_2_UUID,
                        'name' => MeasurementFixtures::OBSERVATION_2_NAME,
                    ],
                    'provider' => [
                        'uuid' => MeasurementFixtures::USER_2_UUID,
                        'name' => MeasurementFixtures::USER_2_NAME,
                    ],
                ],
            ],
            'pagination' => [
                'limit' => 2,
                'page' => 2,
                'numberOfPages' => 2,
            ],
        ]);
    }
}
