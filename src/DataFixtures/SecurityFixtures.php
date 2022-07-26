<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class SecurityFixtures extends Fixture
{
    public const REGISTERED_ACTIVE_USER_EMAIL = 'test@test.com';
    public const REGISTERED_ACTIVE_USER_UUID = 'e9eae405-e6e3-46a6-a232-b3b3c57539dc';
    public const REGISTERED_ACTIVE_USER_NAME = 'test';
    public const REGISTERED_ACTIVE_USER_PASSWORD = 'test';

    public const REGISTERED_INACTIVE_USER_EMAIL = 'lorem@ipsum.com';
    public const REGISTERED_INACTIVE_USER_UUID = 'badfd2a9-23f0-4e9c-97b2-a243944ab2b3';
    public const REGISTERED_INACTIVE_USER_NAME = 'lips';
    public const REGISTERED_INACTIVE_USER_PASSWORD = 'test';

    public const NOT_REGISTERED_USER_EMAIL = 'dolor@sit.com';

    public function load(ObjectManager $manager): void
    {
        $user1 = new User(self::REGISTERED_ACTIVE_USER_EMAIL, Uuid::fromString(self::REGISTERED_ACTIVE_USER_UUID), self::REGISTERED_ACTIVE_USER_NAME, active: true);
        $user1->setPassword('$2y$13$6Pn.ouTaH8mOCImFT5aAgeZk646bFCfv1h1KSg9sDZZe9hf2JgOhq'); // "test"
        $manager->persist($user1);

        $user2 = new User(self::REGISTERED_INACTIVE_USER_EMAIL, Uuid::fromString(self::REGISTERED_INACTIVE_USER_UUID), self::REGISTERED_INACTIVE_USER_NAME, active: false);
        $user2->setPassword('$2y$13$6Pn.ouTaH8mOCImFT5aAgeZk646bFCfv1h1KSg9sDZZe9hf2JgOhq'); // "test"
        $manager->persist($user2);

        $manager->flush();
    }
}
