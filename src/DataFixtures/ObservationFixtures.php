<?php

namespace App\DataFixtures;

use App\Entity\Observation;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ObservationFixtures extends Fixture
{
    public const USER_1_EMAIL = 'test@test.com';
    public const USER_1_UUID = 'e9eae405-e6e3-46a6-a232-b3b3c57539dc';
    public const OBSERVATION_1_UUID = 'ca45b3e2-8b66-42e2-9953-0eb0191108c1';
    public const OBSERVATION_1_NAME = 'Observation 1';
    public const OBSERVATION_2_UUID = '14dd2de1-8cb7-4439-a9bd-beba8e884b8e';
    public const OBSERVATION_2_NAME = 'Observation 2';

    public function load(ObjectManager $manager): void
    {
        $user1 = new User(self::USER_1_EMAIL, self::USER_1_UUID);
        $user1->setPassword('$2y$13$6Pn.ouTaH8mOCImFT5aAgeZk646bFCfv1h1KSg9sDZZe9hf2JgOhq'); // "test"
        $manager->persist($user1);

        $manager->persist(new Observation(
            $user1,
            self::OBSERVATION_1_UUID,
            self::OBSERVATION_1_NAME
        ));

        $manager->persist(new Observation(
            $user1,
            self::OBSERVATION_2_UUID,
            self::OBSERVATION_2_NAME
        ));

        $manager->flush();
    }
}
