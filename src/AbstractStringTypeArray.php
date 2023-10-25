<?php declare(strict_types=1);

namespace Fortuneglobe\Types;

use Fortuneglobe\Types\Exceptions\ValidationException;
use Fortuneglobe\Types\Interfaces\RepresentsArrayType;
use Fortuneglobe\Types\Interfaces\RepresentsStringType;
use Fortuneglobe\Types\Traits\RepresentingStringArrayType;

abstract class AbstractStringTypeArray implements RepresentsArrayType
{
	use RepresentingStringArrayType;

	/**
	 * @param RepresentsStringType[] $stringTypes
	 */
	public function __construct( array $stringTypes )
	{
		self::validate( $stringTypes );

		$this->stringTypes = $this->transform( $stringTypes );
	}

	abstract protected static function isValid( RepresentsStringType $stringType ): bool;

	public function equals( AbstractStringTypeArray $stringTypes ): bool
	{
		if ( $this->count() !== $stringTypes->count() )
		{
			return false;
		}

		foreach ( $stringTypes as $index => $stringType )
		{
			if ( get_class( $this->stringTypes[ $index ] ) !== get_class( $stringType ) )
			{
				return false;
			}
		}

		return $this->equalsValues( $stringTypes );
	}

	public function equalsValues( AbstractStringTypeArray $stringTypes ): bool
	{
		$currentClassAndValues = $this->getClassAndValueStrings( $this );
		$foreignClassAndValues = $this->getClassAndValueStrings( $stringTypes );

		sort( $currentClassAndValues );
		sort( $foreignClassAndValues );

		return $currentClassAndValues === $foreignClassAndValues;
	}

	/**
	 * @param RepresentsStringType[] $stringTypes
	 */
	protected function transform( array $stringTypes ): array
	{
		return $stringTypes;
	}

	protected static function validate( array $stringTypes ): void
	{
		foreach ( $stringTypes as $stringType )
		{
			if ( !($stringType instanceof RepresentsStringType) || !static::isValid( $stringType ) )
			{
				throw new ValidationException(
					sprintf(
						'Not implementing %s: %s',
						RepresentsStringType::class,
						print_r( $stringType, true )
					)
				);
			}
		}
	}

	private function getClassAndValueStrings( AbstractStringTypeArray $stringTypes ): array
	{
		$classAndValues = [];
		foreach ( $stringTypes as $stringType )
		{
			$classAndValues[] = $this->getClassAndValueString( $stringType );
		}

		return $classAndValues;
	}

	private function getClassAndValueString( RepresentsStringType $stringType ): string
	{
		return get_class( $stringType ) . $stringType->toString();
	}
}
