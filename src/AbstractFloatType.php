<?php declare(strict_types=1);

namespace Fortuneglobe\Types;

use Fortuneglobe\Types\Exceptions\ValidationException;
use Fortuneglobe\Types\Interfaces\RepresentsFloatType;
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

	public function equals( RepresentsFloatType $floatType ): bool
	{
		return get_class( $floatType ) === get_class( $this ) && $this->isEqual( $floatType );
	}

	protected function validate( float $value ): void
	{
		if ( !static::isValid( $value ) )
		{
			throw new ValidationException(
				sprintf(
					'Invalid %s: %s',
					(new \ReflectionClass( get_called_class() ))->getShortName(),
					$value
				)
			);
		}
	}
}
