<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Interfaces;

interface RepresentsStringType extends \Stringable, \JsonSerializable
{
	public function equals( RepresentsStringType $type ): bool;

	public function equalsValue( RepresentsStringType|string $value ): bool;

	public function toString(): string;
}
