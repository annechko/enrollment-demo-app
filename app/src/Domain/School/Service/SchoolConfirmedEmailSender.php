<?php

declare(strict_types=1);

namespace App\Domain\School\Service;

use App\Domain\School\Entity\School\SchoolId;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class SchoolConfirmedEmailSender
{
    public function __construct(
        private readonly MailerInterface $mailer,
    ) {
    }

    public function send(string $toEmail, string $invitationToken, SchoolId $schoolId): void
    {
        $email = (new TemplatedEmail())
            ->to($toEmail)
            ->subject('Invitation to the Enrollment Demo App!')
            ->htmlTemplate('email/school/invite-member.html.twig')
            ->context(
                [
                    'schoolId' => $schoolId->getValue(),
                    'invitationToken' => $invitationToken,
                ]
            );

        $this->mailer->send($email);
    }
}
