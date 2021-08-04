<?php declare(strict_types=1);

namespace Fortuneglobe\Types;

use Fortuneglobe\Types\Exceptions\ValidationException;
use Fortuneglobe\Types\Interfaces\RepresentsStringType;
use Fortuneglobe\Types\Traits\RepresentingStringType;

abstract class AbstractStringType implements RepresentsStringType
{
	use RepresentingStringType;

	public function __construct( string $value )
	{
		$this->validate( $value );

		$this->value = $value;
	}

	abstract public function isValid( string $value ): bool;

	/**
	 * @param RepresentsStringType $type
	 *
	 * @return RepresentsStringType|static
	 */
	public static function fromStringType( RepresentsStringType $type ): RepresentsStringType
	{
		return new static( $type->toString() );
	}

	public function equals( RepresentsStringType $type ): bool
	{
		return get_class( $this ) === get_class( $type ) && $this->equalsValue( $type );
	}

	public function equalsValue( RepresentsStringType $type ): bool
	{
		return $this->toString() === $type->toString();
	}

	protected function validate( string $value ): void
	{
		if ( !$this->isValid( $value ) )
		{
			throw new ValidationException(
				sprintf(
					'Invalid %s: %s',
					(new \ReflectionClass( $this ))->getShortName(),
					$value
				)
			);
		}
	}
}
