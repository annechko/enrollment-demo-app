<?php

declare(strict_types=1);

namespace App\Core\School\Service;

use App\Core\School\Entity\School\SchoolId;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SchoolConfirmedEmailSender
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function send(string $toEmail, string $invitationToken, SchoolId $schoolId): void
    {
        $url = $this->urlGenerator->generate('school_member_register', [
            'invitationToken' => $invitationToken,
            'schoolId' => $schoolId->getValue(),
        ]);
        $email = (new TemplatedEmail())
            ->to($toEmail)
            ->subject('Invitation to the Enrollment Demo App!')
            ->htmlTemplate('email/school/invite-member.html.twig')
            ->context(
                [
                    'url' => $url,
                ]
            );

        $this->mailer->send($email);
    }
}
