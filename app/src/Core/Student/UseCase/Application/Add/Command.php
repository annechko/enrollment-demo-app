<?php

declare(strict_types=1);

namespace App\Core\Student\UseCase\Application\Add;

use App\Core\Common\RegexEnum;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /** @var string */
    #[Assert\NotBlank(message: 'Passport Number should not be blank.')]
    #[Assert\Length(min: 4, minMessage: 'Passport number is too short. It should have {{ limit }} characters or more.')]
    public $passportNumber;

    /** @var \DateTimeImmutable */
    #[Assert\Type(\DateTimeImmutable::class)]
    #[Assert\NotBlank(message: 'Passport expiry should not be blank.')]
    public $passportExpiry;

    /** @var string */
    #[Assert\NotBlank(message: 'Full name should not be blank.')]
    #[Assert\Length(min: 3, minMessage: 'Full name is too short. It should have {{ limit }} characters or more.')]
    public $fullName;

    #[Assert\Type('string')]
    public $preferredName;

    /** @var \DateTimeImmutable */
    #[Assert\Type(\DateTimeImmutable::class)]
    #[Assert\NotBlank(message: 'Date of birth should not be blank.')]
    public $dateOfBirth;

    /** @var string */
    #[Assert\NotBlank(message: 'SchoolId should not be blank.')]
    #[Assert\Regex(pattern: RegexEnum::UUID_PATTERN_REG_EXP)]
    public $schoolId;

    /** @var string */
    #[Assert\NotBlank(message: 'CourseId should not be blank.')]
    #[Assert\Regex(pattern: RegexEnum::UUID_PATTERN_REG_EXP)]
    public $courseId;

    /** @var string */
    #[Assert\NotBlank(message: 'IntakeId should not be blank.')]
    #[Assert\Regex(pattern: RegexEnum::UUID_PATTERN_REG_EXP)]
    public $intakeId;

    /** @var string */
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: RegexEnum::UUID_PATTERN_REG_EXP)]
    public $studentId;
}
