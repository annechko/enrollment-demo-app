<?php

declare(strict_types=1);

namespace App\ReadModel\Admin\Statistics\Filter;

class Filter
{
    public function __construct(
        public readonly ReportTypeEnum $type,
        public readonly ?\DateTimeImmutable $since = null,
    ) {
    }
}
