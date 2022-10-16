<?php

namespace App\Command;

use App\Contract\ActivateUserAccountCommandInterface;
use App\Contract\AnonymousUserInterface;
use App\Contract\UserInterface;
use App\Validator\UserActivationLinkExists;
use App\Validator\UserActivationLinkNotExpired;
use App\Validator\UserActivationLinkNotUsed;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints as Assert;

class ActivateUserAccountCommand implements ActivateUserAccountCommandInterface
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[UserActivationLinkExists]
    #[UserActivationLinkNotUsed]
    #[UserActivationLinkNotExpired]
    public UuidInterface $uuid;

    #[Ignore]
    public AnonymousUserInterface $provider;

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getProvider(): UserInterface
    {
        return $this->provider;
    }
}