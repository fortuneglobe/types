<?php declare(strict_types=1);

namespace Fortuneglobe\Types;

use Fortuneglobe\Types\Exceptions\ValidationException;
use Fortuneglobe\Types\Interfaces\RepresentsArrayType;
use Fortuneglobe\Types\Traits\RepresentingArrayType;

abstract class AbstractArrayType implements RepresentsArrayType
{
	use RepresentingArrayType;

	public function __construct( array $genericArray )
	{
		self::validate( $genericArray );

		$this->genericArray = $genericArray;
	}

	abstract public static function isValid( array $genericArray ): bool;

	/**
	 * @param string $json
	 *
	 * @return static
	 */
	public static function fromJson( string $json ): RepresentsArrayType
	{
		return new static( json_decode( $json, true, 512, JSON_THROW_ON_ERROR ) );
	}

	/**
	 * @param RepresentsArrayType $type
	 *
	 * @return RepresentsArrayType|static
	 */
	public static function fromArrayType( RepresentsArrayType $type ): RepresentsArrayType
	{
		return new static( $type->toArray() );
	}

	public function equals( RepresentsArrayType $type ): bool
	{
		return get_class( $this ) === get_class( $type ) && $this->equalsValue( $type );
	}

	public function equalsValue( RepresentsArrayType $type ): bool
	{
		$sortFunction = static function ( $element )
		{
			if ( is_array( $element ) )
			{
				ksort( $element );
			}

			return $element;
		};

		$firstArray  = array_map( $sortFunction, $this->toArray() );
		$secondArray = array_map( $sortFunction, $type->toArray() );
		ksort( $firstArray );
		ksort( $secondArray );

		return $firstArray === $secondArray;
	}

	protected static function validate( array $genericArray ): void
	{
		if ( !static::isValid( $genericArray ) )
		{
			throw new ValidationException(
				sprintf(
					'Invalid %s: %s',
					(new \ReflectionClass( static::class ))->getShortName(),
					print_r( $genericArray, true )
				)
			);
		}
	}
}
