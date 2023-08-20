<?php

declare(strict_types=1);

namespace App\Domain\Common;

enum FeatureToggleType: string
{
    case STUDENT_EMAIL_VERIFICATION = 'studentEmailVerification';
}
