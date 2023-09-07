<?php

declare(strict_types=1);

namespace App\Core\School\Service;

use App\Core\School\Entity\School\Email;
use App\Core\School\Entity\School\InvitationToken;
use App\Core\School\Entity\School\SchoolId;
use App\Infrastructure\RouteEnum;
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

    public function send(Email $toEmail, InvitationToken $invitationToken, SchoolId $schoolId): void
    {
        $url = $this->urlGenerator->generate(
            RouteEnum::SCHOOL_MEMBER_REGISTER,
            [
                'invitationToken' => $invitationToken->getValue(),
                'schoolId' => $schoolId->getValue(),
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $email = (new TemplatedEmail())
            ->to($toEmail->getValue())
            ->subject('Activate your Enrollment Demo App account')
            ->htmlTemplate('email/school/invite-member.html.twig')
            ->context(
                [
                    'url' => $url,
                ]
            );

        $this->mailer->send($email);
    }
}
