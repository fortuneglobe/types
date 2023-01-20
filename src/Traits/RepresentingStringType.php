<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Traits;

use Fortuneglobe\Types\Interfaces\RepresentsStringType;

trait RepresentingStringType
{
	private string $value;

	public function __construct( string $value )
	{
		$this->value = $value;
	}

	public function isEmpty(): bool
	{
		return $this->value === '';
	}

	public function toString(): string
	{
		return $this->value;
	}

	public function __toString(): string
	{
		return $this->value;
	}

	public function getLength(): int
	{
		return strlen( $this->toString() );
	}

	public function contains( string|RepresentsStringType $needle ): bool
	{
		return str_contains( $this->toString(), (string)$needle );
	}

	/**
	 * @param string $delimiter
	 *
	 * @return \Iterator|static[]
	 */
	public function split( string $delimiter ): \Iterator
	{
		foreach ( $this->splitRaw( $delimiter ) as $value )
		{
			yield new static( $value );
		}
	}

	/**
	 * @param string $delimiter
	 *
	 * @return string[]
	 */
	public function splitRaw( string $delimiter ): array
	{
		return explode( $delimiter, $this->toString() );
	}

	public function matchRegularExpression( string|RepresentsStringType $pattern, &$matches = null, int $flags = 0, int $offset = 0 ): bool
	{
		$result = @preg_match( (string)$pattern, $this->toString(), $matches, $flags, $offset );

		if ( false === $result )
		{
			throw new \LogicException( 'Regular expression error: ' . error_get_last()['message'] );
		}

		return $result > 0;
	}
}
