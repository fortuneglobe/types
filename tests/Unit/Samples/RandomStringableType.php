<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit\Samples;

class RandomStringableType
{
	private string $value;

	public function __construct( string $value )
	{
		$this->value = $value;
	}

	public function __toString(): string
	{
		return $this->value;
	}
}
