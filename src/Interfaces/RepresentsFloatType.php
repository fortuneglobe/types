<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Interfaces;

interface RepresentsFloatType
{
	public function equals( RepresentsFloatType $floatType ): bool;

	public function toFloat(): float;

	public function __toString(): string;

	public function isGreaterThan( RepresentsFloatType $floatType ): bool;

	public function isGreaterThanOrEqual( RepresentsFloatType $floatType ): bool;

	public function isLessThan( RepresentsFloatType $floatType ): bool;

	public function isLessThanOrEqual( RepresentsFloatType $floatType ): bool;

	public function isEqual( RepresentsFloatType $floatType ): bool;

}
