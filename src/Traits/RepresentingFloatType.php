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

	public function toString( int $decimals = 0 ): string
	{
		return number_format( $this->value, $decimals, '.', '' );
	}

	public function __toString(): string
	{
		return (string)$this->value;
	}

	public function isGreaterThan( RepresentsFloatType|float $value ): bool
	{
		return $this->value > $this->getValue( $value );
	}

	public function isGreaterThanOrEqual( RepresentsFloatType|float $value ): bool
	{
		return $this->value >= $this->getValue( $value );
	}

	public function isLessThan( RepresentsFloatType|float $value ): bool
	{
		return $this->value < $this->getValue( $value );
	}

	public function isLessThanOrEqual( RepresentsFloatType|float $value ): bool
	{
		return $this->value <= $this->getValue( $value );
	}

	public function isEqual( RepresentsFloatType|float $value ): bool
	{
		return $this->value === $this->getValue( $value );
	}

	public function isZero(): bool
	{
		return $this->value === 0.0;
	}

	public function isPositive(): bool
	{
		return $this->value > 0;
	}

	public function isNegative(): bool
	{
		return $this->value < 0;
	}

	public function isPositiveOrZero(): bool
	{
		return $this->value >= 0.0;
	}

	public function isNegativeOrZero(): bool
	{
		return $this->value <= 0.0;
	}

	protected function getValue( RepresentsFloatType|float $floatType ): float
	{
		return is_float( $floatType ) ? $floatType : $floatType->toFloat();
	}
}
