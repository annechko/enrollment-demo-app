<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\ReadModel\Admin\Statistics\Filter\Filter;
use App\ReadModel\Admin\Statistics\Filter\ReportTypeEnum;
use App\ReadModel\Admin\Statistics\StatsFetcher;

class StatisticsService
{
    private const DATE_STRING_YEAR_AGO = 'first day of this month last year';
    private const FORMAT_MONTH = 'M y';
    private const INTERVAL_MONTH = 'P1M';
    private const FORMAT_DAY = 'j M';
    private const INTERVAL_DAY = 'P1D';
    private const DATE_STRING_MONTH_AGO = '30 days ago';

    public function __construct(
        private readonly StatsFetcher $fetcher,
    ) {
    }

    public function getByType(Filter $filter): StatisticsResponseDto
    {
        return match ($filter->type->value) {
            ReportTypeEnum::SCHOOL_REGISTRATIONS_YEAR->value => $this->getData(
                self::DATE_STRING_YEAR_AGO,
                $this->fetcher->fetchSchoolRegistrationsByMonth(...),
                self::FORMAT_MONTH,
                self::INTERVAL_MONTH,
                'y-m'
            ),
            ReportTypeEnum::SCHOOL_REGISTRATIONS_MONTH->value => $this->getData(
                self::DATE_STRING_MONTH_AGO,
                $this->fetcher->fetchSchoolRegistrationsByDay(...),
                self::FORMAT_DAY,
                self::INTERVAL_DAY,
                'm-d'
            ),
            ReportTypeEnum::STUDENT_APPLICATIONS_YEAR->value => $this->getData(
                self::DATE_STRING_YEAR_AGO,
                $this->fetcher->fetchStudentApplicationsByMonth(...),
                self::FORMAT_MONTH,
                self::INTERVAL_MONTH,
                'y-m'
            ),
            ReportTypeEnum::STUDENT_APPLICATIONS_MONTH->value => $this->getData(
                self::DATE_STRING_MONTH_AGO,
                $this->fetcher->fetchStudentApplicationsByDay(...),
                self::FORMAT_DAY,
                self::INTERVAL_DAY,
                'm-d'
            ),
            default => throw new \DomainException('Unknown report type.')
        };
    }

    private function getData(
        string $dateString,
        callable $fetcherCallback,
        string $labelFormat,
        string $step,
        string $fetcherDateFormat,
    ): StatisticsResponseDto {
        $yearAgo = (new \DateTimeImmutable($dateString))->setTime(0, 0, 0);
        $now = new \DateTimeImmutable();
        $dateToAmountResults = $fetcherCallback(
            $yearAgo
        );
        $datePointer = clone $yearAgo;
        $labels = [];
        $data = [];

        while ($datePointer < $now) {
            $curMonth = $datePointer->format($fetcherDateFormat);
            $labels[$curMonth] = $datePointer->format($labelFormat);
            $datePointer = $datePointer->add(new \DateInterval($step));
        }
        foreach ($labels as $label => $date) {
            $data[] = $dateToAmountResults[$label] ?? 0;
        }

        $maxY = intval(max($data) + round(max($data)) / 2);

        return new StatisticsResponseDto(
            array_values($labels),
            $data,
            $maxY
        );
    }
}
