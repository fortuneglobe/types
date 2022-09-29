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
	 * @param RepresentsIntType $type
	 *
	 * @return RepresentsIntType|static
	 */
	public function add( RepresentsIntType $type ): RepresentsIntType
	{
		return new static( $this->value + $type->toInt() );
	}

	/**
	 * @param RepresentsIntType $type
	 *
	 * @return RepresentsIntType|static
	 */
	public function subtract( RepresentsIntType $type ): RepresentsIntType
	{
		return new static( $this->value - $type->toInt() );
	}

	/**
	 * @param int $value
	 *
	 * @return RepresentsIntType|static
	 */
	public function increment( int $value = 1 ): RepresentsIntType
	{
		return new static( $this->value + $value );
	}

	/**
	 * @param int $value
	 *
	 * @return RepresentsIntType|static
	 */
	public function decrement( int $value = 1 ): RepresentsIntType
	{
		return new static( $this->value - $value );
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
