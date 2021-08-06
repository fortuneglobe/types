<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Interfaces;

interface RepresentsIntType
{
	public function toInt(): int;

	public function __toString(): string;

	public function equals( RepresentsIntType $type ): bool;

	public function isGreaterThan( RepresentsIntType $intType ): bool;

	public function isGreaterThanOrEqual( RepresentsIntType $intType ): bool;

	public function isLessThan( RepresentsIntType $intType ): bool;

	public function isLessThanOrEqual( RepresentsIntType $intType ): bool;

	public function isEqual( RepresentsIntType $intType ): bool;

	public function add( RepresentsIntType $type ): RepresentsIntType;

	public function subtract( RepresentsIntType $type ): RepresentsIntType;

	public function increment( int $value = 1 ): RepresentsIntType;

	public function decrement( int $value = 1 ): RepresentsIntType;
}
