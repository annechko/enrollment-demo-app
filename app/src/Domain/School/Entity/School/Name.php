<?php

declare(strict_types=1);

namespace App\Domain\School\Entity\School;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Embeddable()]
class Name
{
	#[ORM\Column(name: 'name', type: Types::STRING, length: 255)]
	private readonly string $value;

	public function __construct(string $value)
	{
		Assert::stringNotEmpty($value);
		Assert::maxLength($value, 255);
		$this->value = $value;
	}

	public function getValue(): string
	{
		return $this->value;
	}

	public function __toString(): string
	{
		return $this->value;
	}
}