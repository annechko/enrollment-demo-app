<?php

declare(strict_types=1);

namespace App\Security;

use Symfony\Component\Security\Core\Exception\AccountStatusException;

class EmailNotVerifiedException extends AccountStatusException
{

}