<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Traits;

use Fortuneglobe\Types\Exceptions\ValidationException;
use Fortuneglobe\Types\Interfaces\RepresentsStringType;

trait RepresentingStringArrayType
{
	/** @var RepresentsStringType[] */
	private array $stringTypes;

	/**
	 * @param RepresentsStringType[] $stringTypes
	 */
	public function __construct( array $stringTypes = [] )
	{
		$this->stringTypes = $stringTypes;
	}

	/**
	 * @return string[]
	 */
	public function toArray(): array
	{
		$strings = [];
		foreach ( $this->stringTypes as $stringType )
		{
			$strings[] = $stringType->toString();
		}

		return $strings;
	}

	public function current(): RepresentsStringType
	{
		return current( $this->stringTypes );
	}

	public function next(): void
	{
		next( $this->stringTypes );
	}

	public function key()
	{
		return key( $this->stringTypes );
	}

	public function valid(): bool
	{
		return isset( $this->stringTypes[ $this->key() ] );
	}

	public function rewind(): void
	{
		reset( $this->stringTypes );
	}

	public function offsetExists( $offset ): bool
	{
		return isset( $this->stringTypes[ (string)$offset ] );
	}

	public function offsetGet( $offset ): RepresentsStringType
	{
		if ( !isset( $this->stringTypes[ (string)$offset ] ) )
		{
			throw new \LogicException( 'Key not found in array: ' . $offset );
		}

		return $this->stringTypes[ (string)$offset ];
	}

	public function offsetSet( $offset, $value ): void
	{
		if ( !($value instanceof RepresentsStringType) )
		{
			throw new ValidationException( 'Not implementing ' . RepresentsStringType::class . ': ' . print_r( $value, true ) );
		}

		$this->stringTypes[ (string)$offset ] = $value;
	}

	public function offsetUnset( $offset )
	{
		unset( $this->stringTypes[ (string)$offset ] );
	}

	public function count(): int
	{
		return count( $this->stringTypes );
	}

	public function toJson(): string
	{
		return json_encode( $this->toArray(), JSON_THROW_ON_ERROR );
	}

	public function jsonSerialize(): array
	{
		return $this->toArray();
	}
}
