<?php declare(strict_types=1);

namespace Fortuneglobe\Types;

use Fortuneglobe\Types\Exceptions\ValidationException;
use Fortuneglobe\Types\Interfaces\RepresentsArrayType;
use Fortuneglobe\Types\Interfaces\RepresentsFloatType;
use Fortuneglobe\Types\Traits\RepresentingFloatArrayType;

abstract class AbstractFloatTypeArray implements RepresentsArrayType
{
	use RepresentingFloatArrayType;

	/**
	 * @param RepresentsFloatType[] $floatTypes
	 */
	public function __construct( array $floatTypes )
	{
		$this->validate( $floatTypes );

		$this->floatTypes = $floatTypes;
	}

	abstract protected static function isValid( RepresentsFloatType $floatType ): bool;

	public function equals( AbstractFloatTypeArray $floatTypes ): bool
	{
		if ( get_class( $this ) !== get_class( $floatTypes ) )
		{
			return false;
		}

		if ( $this->count() !== $floatTypes->count() )
		{
			return false;
		}

		foreach ( $floatTypes as $index => $floatType )
		{
			if ( get_class( $this->floatTypes[ $index ] ) !== get_class( $floatType ) )
			{
				return false;
			}
		}

		return $this->equalsValues( $floatTypes );
	}

	public function equalsValues( AbstractFloatTypeArray $floatTypes ): bool
	{
		$currentClassAndValues = $this->getClassAndValueStrings( $this );
		$foreignClassAndValues = $this->getClassAndValueStrings( $floatTypes );

		sort( $currentClassAndValues );
		sort( $foreignClassAndValues );

		return $currentClassAndValues === $foreignClassAndValues;
	}

	protected function validate( array $floatTypes ): void
	{
		foreach ( $floatTypes as $floatType )
		{
			if ( !($floatType instanceof RepresentsFloatType) || !static::isValid( $floatType ) )
			{
				throw new ValidationException(
					sprintf(
						'Invalid %s: %s',
						(new \ReflectionClass( get_called_class() ))->getShortName(),
						print_r( $floatTypes, true )
					)
				);
			}
		}
	}

	private function getClassAndValueStrings( AbstractFloatTypeArray $floatTypes ): array
	{
		$classAndValues = [];
		foreach ( $floatTypes as $floatType )
		{
			$classAndValues[] = $this->getClassAndValueString( $floatType );
		}

		return $classAndValues;
	}

	private function getClassAndValueString( RepresentsFloatType $floatType ): string
	{
		return get_class( $floatType ) . $floatType->toFloat();
	}
}
