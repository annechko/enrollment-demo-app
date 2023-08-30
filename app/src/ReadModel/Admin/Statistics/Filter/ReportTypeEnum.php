<?php

declare(strict_types=1);

namespace App\ReadModel\Admin\Statistics\Filter;

enum ReportTypeEnum: string
{
    // yeah-yeah, it is not single responsibility, it has entity and period in one value.
    // as soon as I need other types - I'll refactor it.
    // for now it's ok
    case SCHOOL_REGISTRATIONS_YEAR = 'schoolRegistrationsYear';
    case SCHOOL_REGISTRATIONS_MONTH = 'schoolRegistrationsMonth';
    case STUDENT_APPLICATIONS_MONTH = 'studentApplicationsMonth';
    case STUDENT_APPLICATIONS_YEAR = 'studentApplicationsYear';
}
