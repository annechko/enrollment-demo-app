<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

class StatisticsResponseDto
{
    public function __construct(
        public readonly array $labels,
        public readonly array $data,
        public readonly int $maxY,
    ) {
    }
}
