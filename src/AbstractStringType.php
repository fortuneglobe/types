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

		$this->value = $this->transform( $value );
	}

	abstract public static function isValid( string $value ): bool;

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

	public function equalsValue( RepresentsStringType|string|\Stringable $value ): bool
	{
		return $this->toString() === (is_string( $value ) ? $value : (string)$value);
	}

	public function trim( string $characters = " \n\r\t\v\x00" ): self
	{
		return new static( trim( $this->toString(), $characters ) );
	}

	public function replace( array|string|RepresentsStringType|\Stringable $search, array|string|RepresentsStringType|\Stringable $replace ): self
	{
		return new static( str_replace( is_array( $search ) ? $search : (string)$search, is_array( $replace ) ? $replace : (string)$replace, $this->toString() ) );
	}

	public function substring( int $offset, ?int $length = null ): self
	{
		return new static( substr( $this->toString(), $offset, $length ) );
	}

	public function toLowerCase(): self
	{
		return new static( strtolower( $this->toString() ) );
	}

	public function toUpperCase(): self
	{
		return new static( strtoupper( $this->toString() ) );
	}

	public function capitalizeFirst(): self
	{
		return new static( ucfirst( $this->toString() ) );
	}

	public function deCapitalizeFirst(): self
	{
		return new static( lcfirst( $this->toString() ) );
	}

	public function toKebabCase(): self
	{
		return new static( preg_replace( [ '/(?<!^)[A-Z]/', '/[-_\s]+/', '/--/', ], [ ' $0', '-', '-', ], $this->toString() ) );
	}

	public function toSnakeCase(): self
	{
		return new static( preg_replace( [ '/(?<!^)[A-Z]/', '/[-_\s]+/', '/__/' ], [ ' $0', '_', '_' ], $this->toString() ) );
	}

	public function toUpperCamelCase(): self
	{
		return $this->toCamelCase( false );
	}

	public function toLowerCamelCase(): self
	{
		return $this->toCamelCase( true );
	}

	public function jsonSerialize(): string
	{
		return $this->value;
	}

	protected function transform( string $value ): string
	{
		return $value;
	}

	protected function validate( string $value ): void
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

	private function toCamelCase( bool $isLowerCamelCase ): self
	{
		$result = @preg_match_all( '#[^-_\s]*#', $this->toString(), $matches, PREG_PATTERN_ORDER );

		if ( false === $result )
		{
			throw new \LogicException( 'Regular expression error: ' . error_get_last()['message'] );
		}

		if ( $result > 0 && count( $matches[0] ) > 2 )
		{
			$camelCaseString = '';
			foreach ( $matches[0] as $value )
			{
				if ( '' !== $value )
				{
					$camelCaseString .= ucfirst( strtolower( $value ) );
				}
			}

			return new static( $isLowerCamelCase ? lcfirst( $camelCaseString ) : $camelCaseString );
		}

		return $this;
	}
}
