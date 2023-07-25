<?php

declare(strict_types=1);

namespace App\Domain\Student\UseCase\Student\Register;

use App\Domain\Core\FeatureToggleService;
use App\Domain\Core\FeatureToggleType;
use App\Domain\Core\Flusher;
use App\Domain\Core\UuidGenerator;
use App\Domain\Student\Entity\Student\Student;
use App\Domain\Student\Entity\Student\StudentId;
use App\Domain\Student\Repository\StudentRepository;
use App\Domain\Student\Service\StudentEmailVerifier;
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
