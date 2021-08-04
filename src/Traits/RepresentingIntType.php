<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Traits;

use Fortuneglobe\Types\Interfaces\RepresentsIntType;

trait RepresentingIntType
{
	private int $value;

	public function __construct( int $value )
	{
		$this->value = $value;
	}

	public function toInt(): int
	{
		return $this->value;
	}

	public function __toString(): string
	{
		return (string)$this->value;
	}

	public function isGreaterThan( RepresentsIntType $intType ): bool
	{
		return $this->value > $intType->toInt();
	}

	public function isGreaterThanOrEqual( RepresentsIntType $intType ): bool
	{
		return $this->value >= $intType->toInt();
	}

	public function isLessThan( RepresentsIntType $intType ): bool
	{
		return $this->value < $intType->toInt();
	}

	public function isLessThanOrEqual( RepresentsIntType $intType ): bool
	{
		return $this->value <= $intType->toInt();
	}

	public function isEqual( RepresentsIntType $intType ): bool
	{
		return $this->value === $intType->toInt();
	}
}
