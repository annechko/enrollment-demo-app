<?php

declare(strict_types=1);

namespace App\Security;

use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

class EmailNotVerifiedException extends CustomUserMessageAccountStatusException
{
}
