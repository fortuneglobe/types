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

	public function toString(): string
	{
		return (string)$this->value;
	}

	public function toFloat(): float
	{
		return (float)$this->value;
	}

	public function __toString(): string
	{
		return $this->toString();
	}

	public function isGreaterThan( RepresentsIntType|int $value ): bool
	{
		return $this->value > $this->getValue( $value );
	}

	public function isGreaterThanOrEqual( RepresentsIntType|int $value ): bool
	{
		return $this->value >= $this->getValue( $value );
	}

	public function isLessThan( RepresentsIntType|int $value ): bool
	{
		return $this->value < $this->getValue( $value );
	}

	public function isLessThanOrEqual( RepresentsIntType|int $value ): bool
	{
		return $this->value <= $this->getValue( $value );
	}

	public function isEqual( RepresentsIntType|int $value ): bool
	{
		return $this->value === $this->getValue( $value );
	}

	public function isZero(): bool
	{
		return $this->value === 0;
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
		return $this->value >= 0;
	}

	public function isNegativeOrZero(): bool
	{
		return $this->value <= 0;
	}

	protected function getValue( RepresentsIntType|int $value ): int
	{
		return is_int( $value ) ? $value : $value->toInt();
	}
}
