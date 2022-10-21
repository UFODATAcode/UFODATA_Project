<?php

namespace App\DataFixtures;

use App\Entity\MissionControlAdsBFlightTracking;
use App\Entity\MissionControlData;
use App\Entity\MissionControlWeather;
use App\Entity\Observation;
use App\Entity\RadioFrequencySpectrum;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Entity\File as FileMetadata;

class MeasurementFixtures extends Fixture
{
    public const ADMIN_1_EMAIL = 'admin-1@system.com';
    public const ADMIN_1_UUID = 'ab027fd7-e2ca-4de6-a2e5-b47bb9128b86';
    public const ADMIN_1_NAME = 'admin1';

    public const USER_1_EMAIL = 'test@test.com';
    public const USER_1_UUID = 'e9eae405-e6e3-46a6-a232-b3b3c57539dc';
    public const USER_1_NAME = 'Tester';
    public const USER_2_EMAIL = 'lorem@ipsum.com';
    public const USER_2_UUID = '65896a81-5330-4c87-88db-4aa6d0a97d29';
    public const USER_2_NAME = 'Lorem';

    public const OBSERVATION_1_UUID = 'ca45b3e2-8b66-42e2-9953-0eb0191108c1';
    public const OBSERVATION_1_NAME = 'Observation 1';
    public const OBSERVATION_2_UUID = 'a1de79dc-6519-4795-ba20-6272ef424e93';
    public const OBSERVATION_2_NAME = 'Observation 2';

    public const MEASUREMENT_1_UUID = 'e2dbc62e-3af1-4bba-8fb1-36cf5df18b1e';
    public const MEASUREMENT_1_NAME = 'Initial name';
    public const MEASUREMENT_2_UUID = 'eac7d397-4a08-4567-8e09-005d8117a42e';
    public const MEASUREMENT_2_NAME = 'First name';
    public const MEASUREMENT_3_UUID = '4ce92471-b5fd-48cc-a8e7-70165a4db059';
    public const MEASUREMENT_3_NAME = 'M3 name';
    public const MEASUREMENT_4_UUID = '058033d1-f528-409e-89d0-5176ee2ce3fc';
    public const MEASUREMENT_4_NAME = 'M4 name';

    public const NOT_EXISTING_MEASUREMENT_UUID = 'ab1db128-e844-4dbd-9988-e0758f26a5af';

    private static string $measurementsDirectory = '/var/www/html/public/measurements/';

    public function load(ObjectManager $manager): void
    {
        if (!file_exists(self::$measurementsDirectory) || !is_dir(self::$measurementsDirectory)) {
            mkdir(self::$measurementsDirectory);
        }

        $admin1 = new User(self::ADMIN_1_EMAIL, Uuid::fromString(self::ADMIN_1_UUID), self::ADMIN_1_NAME);
        $admin1->setPassword('$2y$13$6Pn.ouTaH8mOCImFT5aAgeZk646bFCfv1h1KSg9sDZZe9hf2JgOhq'); // "test"
        $admin1->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin1);

        //TODO: check env and not load if != test
        $user1 = new User(self::USER_1_EMAIL, Uuid::fromString(self::USER_1_UUID), self::USER_1_NAME);
        $user1->setPassword('$2y$13$6Pn.ouTaH8mOCImFT5aAgeZk646bFCfv1h1KSg9sDZZe9hf2JgOhq'); // "test"
        $manager->persist($user1);

        $observation1 = new Observation(
            $user1,
            Uuid::fromString(self::OBSERVATION_1_UUID),
            self::OBSERVATION_1_NAME
        );
        $manager->persist($observation1);

        $measurement1rfs1OriginalFileName = 'measurement-rfs.csv';
        $measurement1rfs1FileName = $this->getTestFileName($measurement1rfs1OriginalFileName);
        $measurement1Rfs1 = new RadioFrequencySpectrum(
            Uuid::fromString(self::MEASUREMENT_1_UUID),
            $observation1,
            $user1,
            new File($this->prepareFile($measurement1rfs1OriginalFileName)),
            self::MEASUREMENT_1_NAME
        );
        $originalFileMetadata = new FileMetadata();
        $originalFileMetadata->setName($measurement1rfs1FileName);
        $originalFileMetadata->setOriginalName($measurement1rfs1FileName);
        $originalFileMetadata->setMimeType('text/plain');
        $originalFileMetadata->setSize(100);
        $measurement1Rfs1->setOriginalFileMetadata($originalFileMetadata);
        $manager->persist($measurement1Rfs1);

        $measurement2McData1 = new MissionControlData(
            Uuid::fromString(self::MEASUREMENT_2_UUID),
            $observation1,
            $user1,
            new File(codecept_data_dir('measurement-mc-data.csv')),
            self::MEASUREMENT_2_NAME
        );
        $manager->persist($measurement2McData1);

        $user2 = new User(self::USER_2_EMAIL, Uuid::fromString(self::USER_2_UUID), self::USER_2_NAME);
        $user2->setPassword('$2y$13$6Pn.ouTaH8mOCImFT5aAgeZk646bFCfv1h1KSg9sDZZe9hf2JgOhq'); // "test"
        $manager->persist($user2);

        $observation2 = new Observation(
            $user2,
            Uuid::fromString(self::OBSERVATION_2_UUID),
            self::OBSERVATION_2_NAME
        );
        $manager->persist($observation2);

        $measurement3McFlight1 = new MissionControlAdsBFlightTracking(
            Uuid::fromString(self::MEASUREMENT_3_UUID),
            $observation2,
            $user2,
            new File(codecept_data_dir('measurement-mc-flight-tracking.csv')),
            self::MEASUREMENT_3_NAME
        );
        $manager->persist($measurement3McFlight1);

        $measurement4McWeather1 = new MissionControlWeather(
            Uuid::fromString(self::MEASUREMENT_4_UUID),
            $observation2,
            $user2,
            new File(codecept_data_dir('measurement-mc-weather.csv')),
            self::MEASUREMENT_4_NAME
        );
        $manager->persist($measurement4McWeather1);

        $manager->flush();
    }

    private function prepareFile(string $measurementOriginalFileName): string
    {
        $measurementFilePath = self::$measurementsDirectory . $this->getTestFileName($measurementOriginalFileName);
        copy(codecept_data_dir($measurementOriginalFileName), $measurementFilePath);

        return $measurementFilePath;
    }

    private function getTestFileName(string $originalFileName): string
    {
        return 'test-' . $originalFileName;
    }
}
