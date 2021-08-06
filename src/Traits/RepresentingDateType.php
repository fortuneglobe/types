<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Traits;

use Fortuneglobe\Types\Interfaces\RepresentsDateType;

trait RepresentingDateType
{
	private \DateTimeImmutable $dateTime;

	public function __construct( ?string $dateTime = "now", ?\DateTimeZone $timeZone = null )
	{
		$this->dateTime = new \DateTimeImmutable( $dateTime, $timeZone );
	}

	public function diff( RepresentsDateType $datetime2, bool $absolute = false ): \DateInterval
	{
		return $this->dateTime->diff( $datetime2->toDateTime(), $absolute );
	}

	public function format( string $format ): string
	{
		return $this->dateTime->format( $format );
	}

	public function getOffset(): int
	{
		return $this->dateTime->getOffset();
	}

	public function getTimestamp(): int
	{
		return $this->dateTime->getTimestamp();
	}

	public function getTimezone(): \DateTimeZone
	{
		return $this->dateTime->getTimezone();
	}

	public function __wakeup(): void
	{
		$this->dateTime->__wakeup();
	}

	public function toDateTime(): \DateTimeInterface
	{
		return $this->dateTime;
	}

	public function toString(): string
	{
		return $this->format( 'c' );
	}

	public function __toString(): string
	{
		return $this->toString();
	}
}
