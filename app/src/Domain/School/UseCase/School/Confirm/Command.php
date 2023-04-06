<?php

declare(strict_types=1);

namespace App\Domain\School\UseCase\School\Confirm;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @Assert\NotBlank()
     */
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }
}
