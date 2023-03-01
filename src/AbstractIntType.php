<?php declare(strict_types=1);

namespace Fortuneglobe\Types;

use Fortuneglobe\Types\Exceptions\ValidationException;
use Fortuneglobe\Types\Interfaces\RepresentsIntType;
use Fortuneglobe\Types\Traits\RepresentingIntType;

abstract class AbstractIntType implements RepresentsIntType
{
	use RepresentingIntType;

	public function __construct( int $value )
	{
		$this->validate( $value );

		$this->value = $value;
	}

	abstract public static function isValid( int $value ): bool;

	/**
	 * @param RepresentsIntType $type
	 *
	 * @return RepresentsIntType|static
	 */
	public static function fromIntType( RepresentsIntType $type ): RepresentsIntType
	{
		return new static( $type->toInt() );
	}

	public function equals( RepresentsIntType $type ): bool
	{
		return get_class( $this ) === get_class( $type ) && $this->isEqual( $type );
	}

	/**
	 * @param RepresentsIntType|int $value
	 *
	 * @return RepresentsIntType|static
	 */
	public function add( RepresentsIntType|int $value ): RepresentsIntType
	{
		return new static( $this->value + $this->getValue( $value ) );
	}

	/**
	 * @param RepresentsIntType|int $value
	 *
	 * @return RepresentsIntType|static
	 */
	public function subtract( RepresentsIntType|int $value ): RepresentsIntType
	{
		return new static( $this->value - $this->getValue( $value ) );
	}

	/**
	 * @param RepresentsIntType|int $value
	 *
	 * @return RepresentsIntType|static
	 */
	public function increment( RepresentsIntType|int $value = 1 ): RepresentsIntType
	{
		return new static( $this->value + $this->getValue( $value ) );
	}

	/**
	 * @param RepresentsIntType|int $value
	 *
	 * @return RepresentsIntType|static
	 */
	public function decrement( RepresentsIntType|int $value = 1 ): RepresentsIntType
	{
		return new static( $this->value - $this->getValue( $value ) );
	}

	public function jsonSerialize(): int
	{
		return $this->value;
	}

	protected function validate( int $value ): void
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
