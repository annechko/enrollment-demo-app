<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\ReadModel\Admin\Statistics\Filter\Filter;
use App\ReadModel\Admin\Statistics\Filter\ReportTypeEnum;
use App\ReadModel\Admin\Statistics\StatsFetcher;

class StatisticsService
{
    public function __construct(
        private readonly StatsFetcher $fetcher,
    ) {
    }

    public function getByType(Filter $filter): StatisticsResponseDto
    {
        return match ($filter->type->value) {
            ReportTypeEnum::SCHOOL_REGISTRATIONS_YEAR->value => $this->getRegistrationsPerYear(),
            ReportTypeEnum::SCHOOL_REGISTRATIONS_MONTH->value => $this->getRegistrationsPerMonth(),
            ReportTypeEnum::STUDENT_APPLICATIONS_MONTH->value => $this->getStudentApplicationsPerMonth(
            ),
            ReportTypeEnum::STUDENT_APPLICATIONS_YEAR->value => $this->getStudentApplicationsPerYear(
            ),
            default => throw new \DomainException('Unknown report type.')
        };
    }

    private function getRegistrationsPerYear(): StatisticsResponseDto
    {
        $yearAgo = (new \DateTimeImmutable('first day of this month last year'))->setTime(0, 0, 0);
        $now = new \DateTimeImmutable();
        $dateToAmountResults = $this->fetcher->fetchSchoolRegistrationsByMonth(
            $yearAgo
        );
        $datePointer = clone $yearAgo;
        $labels = [];
        $data = [];

        while ($datePointer < $now) {
            $curMonth = $datePointer->format('y-m');
            $labels[$curMonth] = $datePointer->format('M y');
            $datePointer = $datePointer->add(new \DateInterval('P1M'));
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

    private function getRegistrationsPerMonth(): StatisticsResponseDto
    {
        $sinceDate = (new \DateTimeImmutable())->sub(new \DateInterval('P1M'))->setTime(0, 0, 0);
        $now = new \DateTimeImmutable();
        $dateToAmountResults = $this->fetcher->fetchSchoolRegistrationsByDay(
            $sinceDate
        );
        $datePointer = clone $sinceDate;
        $labels = [];
        $data = [];

        while ($datePointer < $now) {
            $index = $datePointer->format('m-d');
            $labels[$index] = $datePointer->format('j M');
            $datePointer = $datePointer->add(new \DateInterval('P1D'));
        }

        foreach ($labels as $label => $date) {
            $data[] = $dateToAmountResults[$label] ?? 0;
        }

        $maxY = (int) round(max($data) + round(max($data)) / 2);

        return new StatisticsResponseDto(
            array_values($labels),
            $data,
            $maxY
        );
    }

    private function getStudentApplicationsPerYear(): StatisticsResponseDto
    {
        $yearAgo = (new \DateTimeImmutable('first day of this month last year'))->setTime(0, 0, 0);
        $now = new \DateTimeImmutable();
        $dateToAmountResults = $this->fetcher->fetchStudentApplicationsByMonth(
            $yearAgo
        );
        $datePointer = clone $yearAgo;
        $labels = [];
        $data = [];

        while ($datePointer < $now) {
            $curMonth = $datePointer->format('y-m');
            $labels[$curMonth] = $datePointer->format('M y');
            $datePointer = $datePointer->add(new \DateInterval('P1M'));
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

    private function getStudentApplicationsPerMonth(): StatisticsResponseDto
    {
        $sinceDate = (new \DateTimeImmutable())->sub(new \DateInterval('P1M'))->setTime(0, 0, 0);
        $now = new \DateTimeImmutable();
        $dateToAmountResults = $this->fetcher->fetchStudentApplicationsByDay(
            $sinceDate
        );
        $datePointer = clone $sinceDate;
        $labels = [];
        $data = [];

        while ($datePointer < $now) {
            $index = $datePointer->format('m-d');
            $labels[$index] = $datePointer->format('j M');
            $datePointer = $datePointer->add(new \DateInterval('P1D'));
        }

        foreach ($labels as $label => $date) {
            $data[] = $dateToAmountResults[$label] ?? 0;
        }

        $maxY = (int) round(max($data) + round(max($data)) / 2);

        return new StatisticsResponseDto(
            array_values($labels),
            $data,
            $maxY
        );
    }
}
