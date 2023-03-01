<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Interfaces;

interface RepresentsDateType extends \JsonSerializable
{
	public function equals( RepresentsDateType $dateType ): bool;

	public function equalsValue( RepresentsDateType $dateType ): bool;

	public function toDateTime(): \DateTimeInterface;

	public function diff( RepresentsDateType $datetime2, bool $absolute ): \DateInterval;

	public function format( string $format ): string;

	public function getOffset(): int;

	public function getTimestamp(): int;

	public function getTimezone(): \DateTimeZone;

	public function toString(): string;

	public function __toString(): string;
}
