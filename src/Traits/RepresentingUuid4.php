<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Traits;

use Fortuneglobe\Types\Exceptions\ValidationException;
use Fortuneglobe\Types\Interfaces\RepresentsStringType;

trait RepresentingUuid4
{
	private string $value;

	protected function __construct( RepresentsStringType|string $value )
	{
		$this->value = self::getValue( $value );
	}

	final public static function validate( RepresentsStringType|string $value ): void
	{
		$stringValue = self::getValue( $value );

		if ( '00000000-0000-0000-0000-000000000000' === $stringValue )
		{
			return;
		}

		if ( preg_match( '!^[\da-f]{8}-[\da-f]{4}-[\da-f]{4}-[\da-f]{4}-[\da-f]{12}$!i', $stringValue ) )
		{
			return;
		}

		throw new ValidationException( 'Invalid uuid: ' . $stringValue );
	}

	/**
	 * @return static
	 * @throws \Exception
	 * @throws \Exception
	 */
	public static function generate(): self
	{
		$data = random_bytes( 16 );

		$data[6] = chr( ord( $data[6] ) & 0x0f | 0x40 );
		$data[8] = chr( ord( $data[8] ) & 0x3f | 0x80 );

		$uuid = vsprintf( '%s%s-%s-%s-%s-%s%s%s', str_split( bin2hex( $data ), 4 ) );

		return new static( $uuid );
	}

	/**
	 * @param RepresentsStringType|string $uuid4
	 *
	 * @return static
	 */
	public static function fromString( RepresentsStringType|string $uuid4 ): self
	{
		self::validate( $uuid4 );

		return new static( $uuid4 );
	}

	public function toString(): string
	{
		return $this->value;
	}

	public function __toString(): string
	{
		return $this->value;
	}

	protected static function getValue( RepresentsStringType|string $value ): string
	{
		if ( $value instanceof RepresentsStringType )
		{
			return $value->toString();
		}

		return $value;
	}
}
