<?php declare(strict_types=1);

namespace Fortuneglobe\Types;

use Fortuneglobe\Types\Exceptions\ValidationException;
use Fortuneglobe\Types\Interfaces\RepresentsFloatType;
use Fortuneglobe\Types\Interfaces\RepresentsIntType;
use Fortuneglobe\Types\Traits\RepresentingFloatType;

abstract class AbstractFloatType implements RepresentsFloatType
{
	use RepresentingFloatType;

	public function __construct( float $value )
	{
		$this->validate( $value );

		$this->value = $value;
	}

	abstract public static function isValid( float $value ): bool;

	/**
	 * @param RepresentsFloatType $type
	 *
	 * @return RepresentsFloatType|static
	 */
	public static function fromFloatType( RepresentsFloatType $type ): RepresentsFloatType
	{
		return new static( $type->toFloat() );
	}

	public function equals( RepresentsFloatType $type ): bool
	{
		return get_class( $type ) === get_class( $this ) && $this->isEqual( $type );
	}

	/**
	 * @param float|RepresentsFloatType|RepresentsIntType|int $value
	 *
	 * @return RepresentsFloatType|static
	 */
	public function add( float|RepresentsFloatType|RepresentsIntType|int $value ): RepresentsFloatType
	{
		return new static( $this->value + $this->getValue( $value ) );
	}

	/**
	 * @param float|RepresentsFloatType|RepresentsIntType|int $value
	 *
	 * @return RepresentsFloatType
	 */
	public function subtract( float|RepresentsFloatType|RepresentsIntType|int $value ): RepresentsFloatType
	{
		return new static( $this->value - $this->getValue( $value ) );
	}

	/**
	 * @param float|RepresentsFloatType|RepresentsIntType|int $value
	 *
	 * @return RepresentsFloatType
	 */
	public function multiply( float|RepresentsFloatType|RepresentsIntType|int $value ): RepresentsFloatType
	{
		return new static( $this->value * $this->getValue( $value ) );
	}

	/**
	 * @param float|RepresentsFloatType|RepresentsIntType|int $value
	 *
	 * @return RepresentsFloatType
	 */
	public function divide( float|RepresentsFloatType|RepresentsIntType|int $value ): RepresentsFloatType
	{
		return new static( $this->value / $this->getValue( $value ) );
	}

	public function jsonSerialize(): float
	{
		return $this->value;
	}

	protected function validate( float $value ): void
	{
		if ( !static::isValid( $value ) )
		{
			throw new ValidationException(
				sprintf(
					'Invalid %s: %s',
					(new \ReflectionClass( static::class ))->getShortName(),
					$value
				)
			);
		}
	}
}
