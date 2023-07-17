<?php

declare(strict_types=1);

namespace App\TwigExtension;

use App\Infrastructure\SectionProvider;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CurrentSection extends AbstractExtension
{
    public function __construct(
        private readonly SectionProvider $sectionProvider,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'current_section',
                [$this, 'currentSection'],
                ['needs_environment' => false, 'is_safe' => ['html']]
            ),
        ];
    }

    public function currentSection(): string
    {
        return $this->sectionProvider->getCurrentSection() ?? '';
    }
}
