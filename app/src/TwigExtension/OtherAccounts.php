<?php

declare(strict_types=1);

namespace App\TwigExtension;

use App\Infrastructure\OtherAccountsProvider;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class OtherAccounts extends AbstractExtension
{
    public function __construct(
        private readonly OtherAccountsProvider $provider,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'other_accounts',
                [$this, 'otherAccounts'],
                ['needs_environment' => false, 'is_safe' => ['html']]
            ),
        ];
    }

    public function otherAccounts(): string
    {
        $accounts = $this->provider->getOtherAccounts();

        return json_encode($accounts);
    }
}
