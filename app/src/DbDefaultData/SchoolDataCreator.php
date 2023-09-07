<?php

declare(strict_types=1);

namespace App\DbDefaultData;

use App\Core\Common\UuidGenerator;
use App\Core\School\Entity\Campus\Campus;
use App\Core\School\Entity\Campus\CampusId;
use App\Core\School\Entity\Course\Course;
use App\Core\School\Entity\Course\CourseId;
use App\Core\School\Entity\Course\Intake\IntakeId;
use App\Core\School\Entity\School\Email;
use App\Core\School\Entity\School\InvitationToken;
use App\Core\School\Entity\School\Name;
use App\Core\School\Entity\School\School;
use App\Core\School\Entity\School\SchoolId;
use App\Core\School\Entity\School\StaffMember;
use App\Core\School\Entity\School\StaffMemberId;
use App\Core\School\Entity\School\StaffMemberName;
use App\Core\Student\Entity\Application\Application;
use App\Core\Student\Entity\Application\ApplicationId;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class SchoolDataCreator
{
    public function __construct(
        private readonly StudentUserCreator $studentCreator,
        private readonly PasswordHasherFactoryInterface $hasherFactory,
        private readonly UuidGenerator $uuidGenerator,
    ) {
    }

    public function createEntities(): array
    {
        $entities = [];
        $applicationsData = [];
        $now = new \DateTimeImmutable();
        $nowPlus2years = clone $now->add(new \DateInterval('P3Y'));
        $dateOfBirth = \DateTimeImmutable::createFromFormat('Y-m-d', '1990-01-01');
        $campusIndex = 0;
        $coursesCount = count(SchoolDataEnum::COURSES);
        foreach (SchoolDataEnum::SCHOOL_NAMES as $index => $name) {
            $adminNameAndSurname = explode(' ', SchoolDataEnum::ADMIN_NAMES[$index]);
            $email = str_replace(' ', '.', strtolower($name)) . '@example.com';

            $school = School::register(
                new SchoolId($this->uuidGenerator->generate()),
                new Name($name),
                new StaffMemberId($this->uuidGenerator->generate()),
                new StaffMemberName($adminNameAndSurname[0], $adminNameAndSurname[1]),
                new Email($email),
                $this->buildCreatedAt($now, $index % 2 === 0 ? 'M' : 'D')
            );
            $school->confirmRegister(
                new InvitationToken(
                    $this->uuidGenerator->generate(),
                    new \DateTimeImmutable()
                )
            );
            $school->getAdmin()->confirmAccount(
                $this->hasherFactory->getPasswordHasher(StaffMember::class)
                    ->hash(SchoolDataEnum::PASSWORD)
            );
            $campus = new Campus(
                $school->getId(),
                new CampusId($this->uuidGenerator->generate()),
                SchoolDataEnum::CAMPUSES_NAMES[$campusIndex],
                SchoolDataEnum::CAMPUSES_ADDRESSES[$campusIndex],
            );
            ++$campusIndex;
            if ($index % 2 === 0) {
                $campus2 = new Campus(
                    $school->getId(),
                    new CampusId($this->uuidGenerator->generate()),
                    SchoolDataEnum::CAMPUSES_NAMES[$campusIndex],
                    SchoolDataEnum::CAMPUSES_ADDRESSES[$campusIndex],
                );
                ++$campusIndex;
                $entities[] = $campus2;
            }
            $courseIndexes = [];
            while (count($courseIndexes) < 20) {
                $courseIndex = rand(0, $coursesCount - 1);
                $courseIndexes[$courseIndex] = true;
            }
            $courseIndexes = array_keys($courseIndexes);
            foreach ($courseIndexes as $courseIndex) {
                $course = new Course(
                    $school->getId(),
                    new CourseId($this->uuidGenerator->generate()),
                    SchoolDataEnum::COURSES[$courseIndex],
                );
                $daysTillStart = rand(50, 365 * 2);
                $start = $now->add(new \DateInterval("P{$daysTillStart}D"));
                $durationDays = rand(365, 365 * 3);
                $end = $start->add(new \DateInterval("P{$durationDays}D"));
                $intake = $course->addIntake(
                    new IntakeId($this->uuidGenerator->generate()),
                    $start,
                    $end,
                    rand(0, 1) === 0 ? 'Thesis' : 'Full-time',
                    rand(5, 50)
                );
                $applicationsData[] = [$intake, $school, $course];
                $entities[] = $course;
            }
            $entities[] = $school;
            $entities[] = $campus;
        }

        for ($j = 0; $j < 20; ++$j) {
            $intake = $applicationsData[$j][0];
            $data = SchoolDataEnum::STUDENT_DATA[$j];
            $application = new Application(
                new ApplicationId($this->uuidGenerator->generate()),
                $student = $this->studentCreator->create(
                    $data[0],
                    $data[1],
                    strtolower($data[0] . $data[1] . '@example.com')
                ),
                $applicationsData[$j][1],
                $applicationsData[$j][2],
                $intake,
                '123456',
                $nowPlus2years,
                $dateOfBirth,
                $data[0] . ' ' . $data[1],
                null,
                $this->buildCreatedAt($now, 'D')
            );
            $entities[] = $student;
            $entities[] = $application;
        }

        return $entities;
    }

    private function buildCreatedAt(\DateTimeImmutable $now, $subType = 'M'): \DateTimeImmutable
    {
        $registerDay = rand(1, 28);
        $registerMonth = rand(1, 12);
        $createdAt = \DateTimeImmutable::createFromFormat(
            'Y-m-d',
            $now->format('Y-m-') . $registerDay
        )
            ->sub(new \DateInterval("P{$registerMonth}{$subType}"));

        return $createdAt > $now ? clone $now : $createdAt;
    }
}
