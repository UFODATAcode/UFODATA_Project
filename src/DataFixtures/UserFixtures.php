<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class UserFixtures extends Fixture
{
    public const ADMIN_1_EMAIL = 'admin@ufodata.com';
    public const ADMIN_1_UUID = 'ab027fd7-e2ca-4de6-a2e5-b47bb9128b86';
    public const ADMIN_1_NAME = 'admin1';

    public const USER_1_EMAIL = 'test@test.com';
    public const USER_1_UUID = 'e9eae405-e6e3-46a6-a232-b3b3c57539dc';
    public const USER_1_NAME = 'test';

    public const USER_2_EMAIL = 'lorem@ipsum.com';
    public const USER_2_UUID = 'badfd2a9-23f0-4e9c-97b2-a243944ab2b3';
    public const USER_2_NAME = 'lips';

    public const USER_3_EMAIL = 'dolor@sit.com';
    public const USER_3_UUID = 'c5c5c21a-ac8c-428d-854f-dcf1b002159e';
    public const USER_3_NAME = 'Dolor Sit';

    public function load(ObjectManager $manager): void
    {
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

        $user3 = new User(self::USER_3_EMAIL, Uuid::fromString(self::USER_3_UUID), self::USER_3_NAME);
        $user3->setPassword('$2y$13$6Pn.ouTaH8mOCImFT5aAgeZk646bFCfv1h1KSg9sDZZe9hf2JgOhq'); // "test"
        $manager->persist($user3);

        $manager->flush();
    }
}
