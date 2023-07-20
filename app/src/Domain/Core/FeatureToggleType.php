<?php

declare(strict_types=1);

namespace App\Domain\Core;

enum FeatureToggleType: string
{
    case STUDENT_EMAIL_VERIFICATION = 'studentEmailVerification';
}
