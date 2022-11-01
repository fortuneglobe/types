<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Interfaces;

interface RepresentsFloatType
{
	public function equals( RepresentsFloatType $type ): bool;

	public function toFloat(): float;

	public function toString( int $decimals = 0 ): string;

	public function __toString(): string;

	public function isZero(): bool;

	public function isPositive(): bool;

	public function isNegative(): bool;

	public function isPositiveOrZero(): bool;

	public function isNegativeOrZero(): bool;

	public function isGreaterThan( RepresentsFloatType|float $value ): bool;

	public function isGreaterThanOrEqual( RepresentsFloatType|float $value ): bool;

	public function isLessThan( RepresentsFloatType|float $value ): bool;

	public function isLessThanOrEqual( RepresentsFloatType|float $value ): bool;

	public function isEqual( RepresentsFloatType|float $value ): bool;

	public function add( RepresentsFloatType|float $value ): RepresentsFloatType;

	public function subtract( RepresentsFloatType|float $value ): RepresentsFloatType;

	public function multiply( RepresentsFloatType|float $value ): RepresentsFloatType;

	public function divide( RepresentsFloatType|float $value ): RepresentsFloatType;
}
