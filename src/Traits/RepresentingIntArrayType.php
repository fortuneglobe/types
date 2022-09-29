<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Traits;

use Fortuneglobe\Types\Exceptions\ValidationException;
use Fortuneglobe\Types\Interfaces\RepresentsIntType;

trait RepresentingIntArrayType
{
	/** @var RepresentsIntType[] */
	private array $intTypes;

	/**
	 * @param RepresentsIntType[] $intTypes
	 */
	public function __construct( array $intTypes = [] )
	{
		$this->intTypes = $intTypes;
	}

	/**
	 * @return int[]
	 */
	public function toArray(): array
	{
		$integers = [];
		foreach ( $this->intTypes as $intType )
		{
			$integers[] = $intType->toInt();
		}

		return $integers;
	}

	public function current(): RepresentsIntType
	{
		return current( $this->intTypes );
	}

	public function next(): void
	{
		next( $this->intTypes );
	}

	public function key(): int|string|null
	{
		return key( $this->intTypes );
	}

	public function valid(): bool
	{
		return isset( $this->intTypes[ $this->key() ] );
	}

	public function rewind(): void
	{
		reset( $this->intTypes );
	}

	public function offsetExists( $offset ): bool
	{
		return isset( $this->intTypes[ (string)$offset ] );
	}

	public function offsetGet( $offset ): RepresentsIntType
	{
		if ( !isset( $this->intTypes[ (string)$offset ] ) )
		{
			throw new \LogicException( 'Key not found in array: ' . $offset );
		}

		return $this->intTypes[ (string)$offset ];
	}

	public function offsetSet( $offset, $value ): void
	{
		if ( !($value instanceof RepresentsIntType) )
		{
			throw new ValidationException( 'Not implementing ' . RepresentsIntType::class . ': ' . print_r( $value, true ) );
		}

		$this->intTypes[ (string)$offset ] = $value;
	}

	public function offsetUnset( $offset ): void
	{
		unset( $this->intTypes[ (string)$offset ] );
	}

	public function count(): int
	{
		return count( $this->intTypes );
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
