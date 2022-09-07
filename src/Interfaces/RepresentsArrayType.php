<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Interfaces;

interface RepresentsArrayType extends \ArrayAccess, \Iterator, \Countable, \JsonSerializable
{
	public function toArray(): array;

	public function toJson(): string;

	public function jsonSerialize(): array;
}
