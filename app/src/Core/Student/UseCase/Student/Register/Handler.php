<?php

declare(strict_types=1);

namespace App\Core\Student\UseCase\Student\Register;

use App\Core\Common\FeatureToggleService;
use App\Core\Common\FeatureToggleType;
use App\Core\Common\Flusher;
use App\Core\Common\UuidGenerator;
use App\Core\Student\Entity\Student\Student;
use App\Core\Student\Entity\Student\StudentId;
use App\Core\Student\Repository\StudentRepository;
use App\Core\Student\Service\StudentEmailVerifier;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class Handler
{
    public function __construct(
        private readonly StudentRepository $studentRepository,
        private readonly Flusher $flusher,
        private readonly StudentEmailVerifier $emailVerifier,
        private readonly UuidGenerator $uuidGenerator,
        private readonly PasswordHasherFactoryInterface $hasherFactory,
        private readonly FeatureToggleService $featureToggleService,
    ) {
    }

    public function handle(Command $command): Student
    {
        $student = Student::register(
            new StudentId($this->uuidGenerator->generate()),
            $command->email,
            $command->name,
            $command->surname,
            $this->hasherFactory->getPasswordHasher(Student::class)
                ->hash($command->plainPassword)
        );

        $this->studentRepository->save($student);

        if ($this->featureToggleService->isEnabled(
            FeatureToggleType::STUDENT_EMAIL_VERIFICATION
        )) {
            $this->emailVerifier->sendEmailConfirmation(
                $student->getId(),
                $student->getEmail()
            );
        } else {
            $student->verifyEmail();
        }

        $this->flusher->flush();
        return $student;
    }
}
