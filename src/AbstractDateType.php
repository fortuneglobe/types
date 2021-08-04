<?php declare(strict_types=1);

namespace Fortuneglobe\Types;

use Fortuneglobe\Types\Exceptions\ValidationException;
use Fortuneglobe\Types\Interfaces\RepresentsDateType;
use Fortuneglobe\Types\Traits\RepresentingDateType;

abstract class AbstractDateType implements RepresentsDateType
{
	use RepresentingDateType;

	public function __construct( ?string $dateTime = "now", ?\DateTimeZone $timeZone = null )
	{
		$this->validate( $dateTime, $timeZone );

		$this->dateTime = new \DateTimeImmutable( $dateTime, $timeZone );
	}

	abstract public function isValid( \DateTimeInterface $value ): bool;

	/**
	 * @param RepresentsDateType $type
	 *
	 * @return RepresentsDateType|static
	 */
	public static function fromDateType( RepresentsDateType $type ): RepresentsDateType
	{
		return new static( $type->format( 'c' ), $type->getTimezone() );
	}

	public function equals( RepresentsDateType $dateType ): bool
	{
		return get_class( $dateType ) === get_class( $this ) && $this->equalsValue( $dateType );
	}

	public function equalsValue( RepresentsDateType $dateType ): bool
	{
		return $this->getTimezone()->getName() === $dateType->getTimezone()->getName() && $this->format( 'c' ) === $dateType->format( 'c' );
	}

	protected function validate( string $dateTime, ?\DateTimeZone $timeZone ): void
	{
		try
		{
			$dateTimeImmutable = new \DateTimeImmutable( $dateTime, $timeZone );
		}
		catch ( \Throwable $exception )
		{
			throw new ValidationException(
				sprintf(
					'Invalid %s: %s',
					(new \ReflectionClass( $this ))->getShortName(),
					$dateTime
				)
			);
		}

		if ( !$this->isValid( $dateTimeImmutable ) )
		{
			throw new ValidationException(
				sprintf(
					'Invalid %s: %s',
					(new \ReflectionClass( $this ))->getShortName(),
					$dateTime
				)
			);
		}
	}
}