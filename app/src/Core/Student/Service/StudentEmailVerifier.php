<?php

declare(strict_types=1);

namespace App\Core\Student\Service;

use App\Core\Common\Flusher;
use App\Core\Student\Entity\Student\Student;
use App\Core\Student\Entity\Student\StudentId;
use App\Infrastructure\RouteEnum;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class StudentEmailVerifier
{
    public function __construct(
        private readonly VerifyEmailHelperInterface $verifyEmailHelper,
        private readonly MailerInterface $mailer,
        private readonly Flusher $flusher,
    ) {
    }

    public function sendEmailConfirmation(
        StudentId $studentId,
        string $studentEmail,
    ): void {
        $email = (new TemplatedEmail())
            ->to($studentEmail)
            ->subject('Please confirm your email')
            ->htmlTemplate('email/student/registration/confirmation_email.html.twig');

        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            RouteEnum::STUDENT_VERIFY_EMAIL,
            $studentId->getValue(),
            $studentId->getValue(),
            ['id' => $studentId->getValue()]
        );

        $context = $email->getContext();
        $context['signedUrl'] = $signatureComponents->getSignedUrl();
        $context['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
        $context['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();

        $email->context($context);

        $this->mailer->send($email);
    }

    /**
     * @throws VerifyEmailExceptionInterface
     */
    public function handleEmailConfirmation(string $requestUri, Student $student): void
    {
        $studentId = $student->getId()->getValue();

        $this->verifyEmailHelper->validateEmailConfirmation(
            $requestUri,
            $studentId,
            $studentId
        );

        $student->verifyEmail();

        $this->flusher->flush();
    }
}
