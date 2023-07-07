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

	public function equals( RepresentsDateType $type ): bool
	{
		return get_class( $type ) === get_class( $this ) && $this->equalsValue( $type );
	}

	public function equalsValue( RepresentsDateType|\DateTimeInterface|string $value ): bool
	{
		return $this->format( 'c' ) === (is_string( $value ) ? (new \DateTimeImmutable( $value ))->format( 'c' ) : $value->format( 'c' ));
	}

	public function isGreaterThan( RepresentsDateType|\DateTimeInterface|string $value ): bool
	{
		return $this > (is_string( $value ) ? new \DateTimeImmutable( $value ) : $value);
	}

	public function isGreaterThanOrEqual( RepresentsDateType|\DateTimeInterface|string $value ): bool
	{
		return $this->isGreaterThan( $value ) || $this->equalsValue( $value );
	}

	public function isLessThan( RepresentsDateType|\DateTimeInterface|string $value ): bool
	{
		return $this < (is_string( $value ) ? new \DateTimeImmutable( $value ) : $value);
	}

	public function isLessThanOrEqual( RepresentsDateType|\DateTimeInterface|string $value ): bool
	{
		return $this->isLessThan( $value ) || $this->equalsValue( $value );
	}

	public function hasExpired( ?\DateInterval $expirationInterval = null, null|RepresentsDateType|\DateTimeInterface $now = null ): bool
	{
		$now = $now ?? new \DateTimeImmutable();

		if ( null !== $expirationInterval )
		{
			return $this->dateTime->add( $expirationInterval ) < $now;
		}

		return $this->dateTime < $now;
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
