<?php

namespace App\Handler;

use App\Contract\UserActivationLinkRepositoryInterface;
use App\Contract\UserRepositoryInterface;
use App\Entity\UserActivationLink;
use App\Event\UserRegisteredEvent;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsMessageHandler]
class SendUserActivationEmailHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserActivationLinkRepositoryInterface $userActivationLinkRepository,
        private readonly TranslatorInterface $translator,
        private readonly MailerInterface $mailer,
        private readonly string $fromEmailAddress,
        private readonly string $userActivationLinkUrl,
    ) {}

    public function __invoke(UserRegisteredEvent $event): void
    {
        $user = $this->userRepository->findOneByUuid($event->getUserUuid());

        if (null === $user) {
            throw new \Exception(\sprintf('User with uuid "%s" not found', $event->getUserUuid()));
        }

        $userActivationLink = new UserActivationLink($this->userActivationLinkUrl, $user);
        $this->userActivationLinkRepository->add($userActivationLink, true);
        $email = (new Email())
            ->from($this->fromEmailAddress)
            ->to($user->getEmail())
            ->subject($this->translator->trans(
                'user.registration.activation_email.subject',
            ))
            ->text($this->translator->trans(
                'user.registration.activation_email.body',
                ['userActivationLink' => $userActivationLink],
            ));

        $this->mailer->send($email);
        $userActivationLink->markAsSent();
        $this->userActivationLinkRepository->update($userActivationLink);
    }
}
