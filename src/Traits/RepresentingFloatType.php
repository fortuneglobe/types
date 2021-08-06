<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Traits;

use Fortuneglobe\Types\Interfaces\RepresentsFloatType;

trait RepresentingFloatType
{
	private float $value;

	public function __construct( float $value )
	{
		$this->value = $value;
	}

	public function toFloat(): float
	{
		return $this->value;
	}

	public function __toString(): string
	{
		return (string)$this->value;
	}

	public function isGreaterThan( RepresentsFloatType $floatType ): bool
	{
		return $this->value > $floatType->toFloat();
	}

	public function isGreaterThanOrEqual( RepresentsFloatType $floatType ): bool
	{
		return $this->value >= $floatType->toFloat();
	}

	public function isLessThan( RepresentsFloatType $floatType ): bool
	{
		return $this->value < $floatType->toFloat();
	}

	public function isLessThanOrEqual( RepresentsFloatType $floatType ): bool
	{
		return $this->value <= $floatType->toFloat();
	}

	public function isEqual( RepresentsFloatType $floatType ): bool
	{
		return $this->value === $floatType->toFloat();
	}
}
