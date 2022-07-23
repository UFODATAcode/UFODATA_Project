<?php

namespace App\DataFixtures;

use App\Entity\Observation;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class ObservationFixtures extends Fixture
{
    public const ADMIN_1_EMAIL = 'admin@ufodata.com';
    public const ADMIN_1_UUID = 'ab027fd7-e2ca-4de6-a2e5-b47bb9128b86';
    public const ADMIN_1_NAME = 'admin1';

    public const USER_1_EMAIL = 'test@test.com';
    public const USER_1_UUID = 'e9eae405-e6e3-46a6-a232-b3b3c57539dc';
    public const USER_1_NAME = 'test';
    public const USER_2_EMAIL = 'lorem@ipsum.com';
    public const USER_2_UUID = '65896a81-5330-4c87-88db-4aa6d0a97d29';
    public const USER_2_NAME = 'Lorem I.';

    public const OBSERVATION_1_UUID = 'ca45b3e2-8b66-42e2-9953-0eb0191108c1';
    public const OBSERVATION_1_NAME = 'Observation 1';
    public const OBSERVATION_2_UUID = '14dd2de1-8cb7-4439-a9bd-beba8e884b8e';
    public const OBSERVATION_2_NAME = 'Observation 2';
    public const OBSERVATION_3_UUID = 'e70b793a-d5f8-402c-8979-d181adbf3f9e';
    public const OBSERVATION_3_NAME = 'Observation 3';
    public const OBSERVATION_4_UUID = '7037eb32-4677-4e82-b4e2-9cb7b2d2f4fa';
    public const OBSERVATION_4_NAME = 'Observation 4';

    public const NOT_EXISTING_OBSERVATION_UUID = 'ab1db128-e844-4dbd-9988-e0758f26a5af';

    public function load(ObjectManager $manager): void
    {
        //TODO: check env and not load if != test
        $admin1 = new User(self::ADMIN_1_EMAIL, Uuid::fromString(self::ADMIN_1_UUID), self::ADMIN_1_NAME);
        $admin1->setPassword('$2y$13$6Pn.ouTaH8mOCImFT5aAgeZk646bFCfv1h1KSg9sDZZe9hf2JgOhq'); // "test"
        $admin1->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin1);

        $user1 = new User(self::USER_1_EMAIL, Uuid::fromString(self::USER_1_UUID), self::USER_1_NAME);
        $user1->setPassword('$2y$13$6Pn.ouTaH8mOCImFT5aAgeZk646bFCfv1h1KSg9sDZZe9hf2JgOhq'); // "test"
        $manager->persist($user1);

        $user2 = new User(self::USER_2_EMAIL, Uuid::fromString(self::USER_2_UUID), self::USER_2_NAME);
        $user2->setPassword('$2y$13$6Pn.ouTaH8mOCImFT5aAgeZk646bFCfv1h1KSg9sDZZe9hf2JgOhq'); // "test"
        $manager->persist($user2);

        $manager->persist(new Observation(
            $user1,
            Uuid::fromString(self::OBSERVATION_1_UUID),
            self::OBSERVATION_1_NAME
        ));

        $manager->persist(new Observation(
            $user1,
            Uuid::fromString(self::OBSERVATION_2_UUID),
            self::OBSERVATION_2_NAME
        ));

        $manager->persist(new Observation(
            $user2,
            Uuid::fromString(self::OBSERVATION_3_UUID),
            self::OBSERVATION_3_NAME
        ));

        $manager->persist(new Observation(
            $user2,
            Uuid::fromString(self::OBSERVATION_4_UUID),
            self::OBSERVATION_4_NAME
        ));

        $manager->flush();
    }
}
