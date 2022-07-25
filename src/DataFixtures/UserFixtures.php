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

    public function load(ObjectManager $manager): void
    {
        $admin1 = new User(self::ADMIN_1_EMAIL, Uuid::fromString(self::ADMIN_1_UUID), self::ADMIN_1_NAME);
        $admin1->setPassword('$2y$13$6Pn.ouTaH8mOCImFT5aAgeZk646bFCfv1h1KSg9sDZZe9hf2JgOhq'); // "test"
        $admin1->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin1);

        $user1 = new User(self::USER_1_EMAIL, Uuid::fromString(self::USER_1_UUID), self::USER_1_NAME);
        $user1->setPassword('$2y$13$6Pn.ouTaH8mOCImFT5aAgeZk646bFCfv1h1KSg9sDZZe9hf2JgOhq'); // "test"
        $manager->persist($user1);

        $manager->flush();
    }
}
