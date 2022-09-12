<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class RegisterUserFixtures extends Fixture
{
    public const NOT_REGISTERED_USER_EMAIL = 'dolor@sit.com';
    public const NOT_REGISTERED_USER_UUID = 'e9eae405-e6e3-46a6-a232-b3b3c57539dc';
    public const NOT_REGISTERED_USER_NAME = 'Dolor Sit';

    public const REGISTERED_USER_EMAIL = 'lorem@ipsum.com';
    public const REGISTERED_USER_UUID = 'badfd2a9-23f0-4e9c-97b2-a243944ab2b3';
    public const REGISTERED_USER_NAME = 'lips';

    public function load(ObjectManager $manager): void
    {
        $registeredUser = new User(self::REGISTERED_USER_EMAIL, Uuid::fromString(self::REGISTERED_USER_UUID), self::REGISTERED_USER_NAME);
        $registeredUser->setPassword('$2y$13$6Pn.ouTaH8mOCImFT5aAgeZk646bFCfv1h1KSg9sDZZe9hf2JgOhq'); // "test"
        $manager->persist($registeredUser);

        $manager->flush();
    }
}
