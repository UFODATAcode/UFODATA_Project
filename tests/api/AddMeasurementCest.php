<?php

namespace App\Tests\Api;

use App\DataFixtures\MeasurementFixtures;
use App\Entity\Measurement;
use App\Entity\MissionControlAdsBFlightTracking;
use App\Entity\MissionControlData;
use App\Entity\MissionControlWeather;
use App\Entity\RadioFrequencySpectrum;
use App\Enum\MeasurementType;
use App\Tests\ApiTester;
use Codeception\Example;
use PHPUnit\Framework\Assert;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;

class AddMeasurementCest
{
    public function iCanNotAddMeasurementWhenIAmNotAuthorized(ApiTester $I): void
    {
        $newMeasurementUuid = 'c3a67222-f6f1-44c1-9025-16efaaa2c838';
        $measurementFileName = 'measurement-mc-data.csv';
        $measurementFileCopyName = 'copy-' . $measurementFileName;
        $measurementFileCopyPath = codecept_data_dir($measurementFileCopyName);

        \copy(
            codecept_data_dir($measurementFileName),
            $measurementFileCopyPath
        );

        $I->haveHttpHeader('Content-Type', 'application/json');

        try {
            $I->sendPost(
                '/measurements',
                [
                    'uuid' => $newMeasurementUuid,
                    'observationUuid' => MeasurementFixtures::OBSERVATION_1_UUID,
                    'measurementType' => MeasurementType::MissionControlData->value,
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
            $I->seeResponseCodeIs(Response::HTTP_UNAUTHORIZED);
            $I->dontSeeInRepository(MissionControlData::class, ['uuid' => $newMeasurementUuid]);
        } finally {
            \unlink($measurementFileCopyPath);
        }
    }

    /**
     * @dataProvider iCanAddMeasurementWhenIAmAuthorizedDataProvider
     */
    public function iCanAddMeasurementWhenIAmAuthorized(ApiTester $I, Example $example)
    {
        $measurementFileName = $example['fileName'];
        $measurementFileCopyName = 'copy-' . $measurementFileName;
        $measurementFileCopyPath = codecept_data_dir($measurementFileCopyName);

        \copy(
            codecept_data_dir($measurementFileName),
            $measurementFileCopyPath
        );

        $I->loadFixtures(MeasurementFixtures::class);
        $I->dontSeeInRepository($example['className'], ['uuid' => $example['uuid']]);

        $I->setBearerTokenForUser(MeasurementFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');

        try {
            $I->sendPost(
                '/measurements',
                [
                    'uuid' => $example['uuid'],
                    'observationUuid' => $example['observationUuid'],
                    'measurementType' => $example['measurementType'],
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
            $I->seeInRepository($example['className'], ['uuid' => $example['uuid']]);
            /** @var Measurement $entity */
            $entity = $I->grabEntityFromRepository($example['className'], ['uuid' => $example['uuid']]);
            Assert::assertInstanceOf($example['className'], $entity);
            Assert::assertInstanceOf(EmbeddedFile::class, $entity->getOriginalFileMetadata());
            $uploadedFilePath = codecept_root_dir('public/measurements/' . $entity->getOriginalFileMetadata()->getName());
            Assert::assertTrue(\file_exists($uploadedFilePath));
            Assert::assertInstanceOf(File::class, $entity->getOriginalFile());
        } finally {
            \unlink($uploadedFilePath);
        }
    }

    public function iGetAnErrorWhenITryToCreateMeasurementWithAlreadyExistingUuid(ApiTester $I): void
    {
        $measurementFileName = 'measurement-mc-data.csv';
        $measurementFileCopyName = 'copy-' . $measurementFileName;
        $measurementFileCopyPath = codecept_data_dir($measurementFileCopyName);

        \copy(
            codecept_data_dir($measurementFileName),
            $measurementFileCopyPath
        );

        $I->loadFixtures(MeasurementFixtures::class);
        $I->seeInRepository(RadioFrequencySpectrum::class, ['uuid' => MeasurementFixtures::MEASUREMENT_1_UUID]);

        $I->setBearerTokenForUser(MeasurementFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');

        try {
            $I->sendPost(
                '/measurements',
                [
                    'uuid' => MeasurementFixtures::MEASUREMENT_1_UUID,
                    'observationUuid' => MeasurementFixtures::OBSERVATION_1_UUID,
                    'measurementType' => MeasurementType::RadioFrequencySpectrum->value,
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
            $I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
            $I->seeResponseContainsJson([
                'errors' => [
                    [
                        'property' => 'uuid',
                        'message' => 'A resource with "' . MeasurementFixtures::MEASUREMENT_1_UUID . '" UUID already exists.',
                        'code' => '74ae47e1-6d43-4dfc-831e-7db274ff494b',
                    ]
                ]
            ]);
        } finally {
            \unlink($measurementFileCopyPath);
        }
    }

    public function iGetAnErrorWhenITryToCreateMeasurementWithNotExistingObservationUuid(ApiTester $I): void
    {
        $newMeasurementUuid = '6d921f3d-340d-487b-abcb-f481f6f965c4';
        $notExistObservationUuid = '74ae47e1-6d43-4dfc-831e-7db274ff494b';

        $measurementFileName = 'measurement-mc-data.csv';
        $measurementFileCopyName = 'copy-' . $measurementFileName;
        $measurementFileCopyPath = codecept_data_dir($measurementFileCopyName);

        copy(
            codecept_data_dir($measurementFileName),
            $measurementFileCopyPath
        );

        $I->loadFixtures(MeasurementFixtures::class);
        $I->seeInRepository(RadioFrequencySpectrum::class, ['uuid' => MeasurementFixtures::MEASUREMENT_1_UUID]);

        $I->setBearerTokenForUser(MeasurementFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');

        try {
            $I->sendPost(
                '/measurements',
                [
                    'uuid' => $newMeasurementUuid,
                    'observationUuid' => $notExistObservationUuid,
                    'measurementType' => MeasurementType::RadioFrequencySpectrum->value,
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
            $I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
            $I->seeResponseContainsJson([
                'errors' => [
                    [
                        'property' => 'observationUuid',
                        'message' => 'A resource with "' . $notExistObservationUuid . '" UUID does not exist.',
                        'code' => 'ab1db128-e844-4dbd-9988-e0758f26a5af',
                    ]
                ]
            ]);
            $I->dontSeeInRepository(RadioFrequencySpectrum::class, ['uuid' => $newMeasurementUuid]);
        } finally {
            \unlink($measurementFileCopyPath);
        }
    }

    public function iGetAnErrorWhenITryToCreateMeasurementWithInvalidMeasurementType(ApiTester $I): void
    {
        $newMeasurementUuid = '6d921f3d-340d-487b-abcb-f481f6f965c4';

        $measurementFileName = 'measurement-mc-data.csv';
        $measurementFileCopyName = 'copy-' . $measurementFileName;
        $measurementFileCopyPath = codecept_data_dir($measurementFileCopyName);

        copy(
            codecept_data_dir($measurementFileName),
            $measurementFileCopyPath
        );

        $I->loadFixtures(MeasurementFixtures::class);
        $I->seeInRepository(RadioFrequencySpectrum::class, ['uuid' => MeasurementFixtures::MEASUREMENT_1_UUID]);

        $I->setBearerTokenForUser(MeasurementFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');

        try {
            $I->sendPost(
                '/measurements',
                [
                    'uuid' => $newMeasurementUuid,
                    'observationUuid' => MeasurementFixtures::OBSERVATION_1_UUID,
                    'measurementType' => 'invalid-measurement-type',
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
            $I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
            $I->seeResponseContainsJson([
                'errors' => [
                    [
                        'property' => 'measurementType',
                        'message' => 'The value you selected is not a valid choice.',
                        'code' => '8e179f1b-97aa-4560-a02f-2a8b42e49df7',
                    ]
                ]
            ]);
            $I->dontSeeInRepository(RadioFrequencySpectrum::class, ['uuid' => $newMeasurementUuid]);
        } finally {
            \unlink($measurementFileCopyPath);
        }
    }

    public function iGetAnErrorWhenITryToCreateMeasurementWithoutSendingFile(ApiTester $I): void
    {
        $newMeasurementUuid = '6d921f3d-340d-487b-abcb-f481f6f965c4';

        $measurementFileName = 'measurement-mc-data.csv';
        $measurementFileCopyName = 'copy-' . $measurementFileName;
        $measurementFileCopyPath = codecept_data_dir($measurementFileCopyName);

        copy(
            codecept_data_dir($measurementFileName),
            $measurementFileCopyPath
        );

        $I->loadFixtures(MeasurementFixtures::class);
        $I->seeInRepository(RadioFrequencySpectrum::class, ['uuid' => MeasurementFixtures::MEASUREMENT_1_UUID]);

        $I->setBearerTokenForUser(MeasurementFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');

        try {
            $I->sendPost(
                '/measurements',
                [
                    'uuid' => $newMeasurementUuid,
                    'observationUuid' => MeasurementFixtures::OBSERVATION_1_UUID,
                    'measurementType' => MeasurementType::RadioFrequencySpectrum->value,
                ]
            );
            $I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
            $I->seeResponseContainsJson([
                'errors' => [
                    [
                        'property' => 'measurement',
                        'message' => 'This value should not be null.',
                        'code' => 'ad32d13f-c3d4-423b-909a-857b961eb720',
                    ]
                ]
            ]);
            $I->dontSeeInRepository(RadioFrequencySpectrum::class, ['uuid' => $newMeasurementUuid]);
        } finally {
            \unlink($measurementFileCopyPath);
        }
    }

    protected function iCanAddMeasurementWhenIAmAuthorizedDataProvider(): array
    {
        return [
            [
                'className' => RadioFrequencySpectrum::class,
                'fileName' => 'measurement-rfs.csv',
                'uuid' => '6d921f3d-340d-487b-abcb-f481f6f965c4',
                'observationUuid' => MeasurementFixtures::OBSERVATION_1_UUID,
                'measurementType' => MeasurementType::RadioFrequencySpectrum->value,
            ],
            [
                'className' => MissionControlData::class,
                'fileName' => 'measurement-mc-data.csv',
                'uuid' => '3231ba2d-b8cf-438c-83ef-abd0b23b428e',
                'observationUuid' => MeasurementFixtures::OBSERVATION_1_UUID,
                'measurementType' => MeasurementType::MissionControlData->value,
            ],
            [
                'className' => MissionControlAdsBFlightTracking::class,
                'fileName' => 'measurement-mc-flight-tracking.csv',
                'uuid' => '2c63aa44-e95b-4719-ae36-d37718cd6e49',
                'observationUuid' => MeasurementFixtures::OBSERVATION_1_UUID,
                'measurementType' => MeasurementType::MissionControlAdsBFlightTracking->value,
            ],
            [
                'className' => MissionControlWeather::class,
                'fileName' => 'measurement-mc-weather.csv',
                'uuid' => '76cec50d-bd05-468a-900c-532bcac3701c',
                'observationUuid' => MeasurementFixtures::OBSERVATION_1_UUID,
                'measurementType' => MeasurementType::MissionControlWeather->value,
            ],
        ];
    }
}
