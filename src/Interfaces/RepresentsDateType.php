<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Interfaces;

interface RepresentsDateType extends \JsonSerializable, \Stringable
{
	public function equals( RepresentsDateType $type ): bool;

	public function equalsValue( RepresentsDateType $value ): bool;

	public function toDateTime(): \DateTimeInterface;

	public function sub( \DateInterval $dateInterval ): RepresentsDateType;

	public function add( \DateInterval $dateInterval ): RepresentsDateType;

	public function diff( RepresentsDateType $datetime2, bool $absolute ): \DateInterval;

	public function isLessThan( RepresentsDateType|\DateTimeInterface|string $value ): bool;

	public function isGreaterThan( RepresentsDateType|\DateTimeInterface|string $value ): bool;

	public function isGreaterThanOrEqual( RepresentsDateType|\DateTimeInterface|string $value ): bool;

	public function isLessThanOrEqual( RepresentsDateType|\DateTimeInterface|string $value ): bool;

	public function hasExpired( ?\DateInterval $expirationInterval = null, null|RepresentsDateType|\DateTimeInterface $now = null ): bool;

	public function format( string $format ): string;

	public function getOffset(): int;

	public function getTimestamp(): int;

	public function getTimezone(): \DateTimeZone;

	public function toString(): string;
}
