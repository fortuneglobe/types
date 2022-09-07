<?php declare(strict_types=1);

namespace Fortuneglobe\Types;

use Fortuneglobe\Types\Exceptions\ValidationException;
use Fortuneglobe\Types\Interfaces\RepresentsArrayType;
use Fortuneglobe\Types\Interfaces\RepresentsIntType;
use Fortuneglobe\Types\Traits\RepresentingIntArrayType;

abstract class AbstractIntTypeArray implements RepresentsArrayType
{
	use RepresentingIntArrayType;

	/**
	 * @param RepresentsIntType[] $intTypes
	 */
	public function __construct( array $intTypes )
	{
		self::validate( $intTypes );

		$this->intTypes = $intTypes;
	}

	abstract protected static function isValid( RepresentsIntType $intType ): bool;

	public function equals( AbstractIntTypeArray $intTypes ): bool
	{
		if ( $this->count() !== $intTypes->count() )
		{
			return false;
		}

		foreach ( $intTypes as $index => $intType )
		{
			if ( get_class( $this->intTypes[ $index ] ) !== get_class( $intType ) )
			{
				return false;
			}
		}

		return $this->equalsValues( $intTypes );
	}

	public function equalsValues( AbstractIntTypeArray $intTypes ): bool
	{
		$currentClassAndValues = $this->getClassAndValueStrings( $this );
		$foreignClassAndValues = $this->getClassAndValueStrings( $intTypes );

		sort( $currentClassAndValues );
		sort( $foreignClassAndValues );

		return $currentClassAndValues === $foreignClassAndValues;
	}

	public static function validate( array $intTypes ): void
	{
		foreach ( $intTypes as $intType )
		{
			if ( !($intType instanceof RepresentsIntType) || !static::isValid( $intType ) )
			{
				throw new ValidationException(
					sprintf(
						'Not implementing %s: %s',
						RepresentsIntType::class,
						print_r( $intType, true )
					)
				);
			}
		}
	}

	private function getClassAndValueStrings( AbstractIntTypeArray $intTypes ): array
	{
		$classAndValues = [];
		foreach ( $intTypes as $intType )
		{
			$classAndValues[] = $this->getClassAndValueString( $intType );
		}

		return $classAndValues;
	}

	private function getClassAndValueString( RepresentsIntType $intType ): string
	{
		return get_class( $intType ) . $intType->toInt();
	}
}
