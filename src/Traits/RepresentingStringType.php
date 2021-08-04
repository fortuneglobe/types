<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Traits;

trait RepresentingStringType
{
	private string $value;

	public function __construct( string $value )
	{
		$this->value = $value;
	}

	public function isEmpty(): bool
	{
		return $this->value === '';
	}

	public function toString(): string
	{
		return $this->value;
	}

	public function __toString(): string
	{
		return $this->value;
	}
}
