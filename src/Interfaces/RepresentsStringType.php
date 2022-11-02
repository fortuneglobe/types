<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Interfaces;

interface RepresentsStringType
{
	public function equals( RepresentsStringType $type ): bool;

	public function equalsValue( RepresentsStringType|string $value ): bool;

	public function toString(): string;

	public function __toString(): string;
}
