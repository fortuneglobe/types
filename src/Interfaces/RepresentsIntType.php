<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Interfaces;

interface RepresentsIntType extends \JsonSerializable
{
	public function toInt(): int;

	public function toString(): string;

	public function toFloat(): float;

	public function __toString(): string;

	public function equals( RepresentsIntType $type ): bool;

	public function isGreaterThan( RepresentsIntType|int $value ): bool;

	public function isGreaterThanOrEqual( RepresentsIntType|int $value ): bool;

	public function isLessThan( RepresentsIntType|int $value ): bool;

	public function isLessThanOrEqual( RepresentsIntType|int $value ): bool;

	public function isEqual( RepresentsIntType|int $value ): bool;

	public function isZero(): bool;

	public function isPositive(): bool;

	public function isNegative(): bool;

	public function isPositiveOrZero(): bool;

	public function isNegativeOrZero(): bool;

	public function add( RepresentsIntType|int $value ): RepresentsIntType;

	public function subtract( RepresentsIntType|int $value ): RepresentsIntType;

	public function increment( RepresentsIntType|int $value = 1 ): RepresentsIntType;

	public function decrement( RepresentsIntType|int $value = 1 ): RepresentsIntType;
}
