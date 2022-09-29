<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Traits;

trait RepresentingArrayType
{
	private array $genericArray;

	public function __construct( array $genericArray = [] )
	{
		$this->genericArray = $genericArray;
	}

	public function toArray(): array
	{
		return $this->genericArray;
	}

	public function current(): mixed
	{
		return current( $this->genericArray );
	}

	public function next(): void
	{
		next( $this->genericArray );
	}

	public function key(): string|int|null
	{
		return key( $this->genericArray );
	}

	public function valid(): bool
	{
		return isset( $this->genericArray[ $this->key() ] );
	}

	public function rewind(): void
	{
		reset( $this->genericArray );
	}

	public function offsetExists( $offset ): bool
	{
		return isset( $this->genericArray[ (string)$offset ] );
	}

	public function offsetGet( $offset ): mixed
	{
		if ( !isset( $this->genericArray[ (string)$offset ] ) )
		{
			throw new \LogicException( 'Key not found in array: ' . $offset );
		}

		return $this->genericArray[ (string)$offset ];
	}

	public function offsetSet( $offset, $value ): void
	{
		$this->genericArray[ (string)$offset ] = $value;
	}

	public function offsetUnset( $offset ): void
	{
		unset( $this->genericArray[ (string)$offset ] );
	}

	public function count(): int
	{
		return count( $this->genericArray );
	}

	public function toJson(): string
	{
		return json_encode( $this, JSON_THROW_ON_ERROR );
	}

	public function jsonSerialize(): array
	{
		return $this->genericArray;
	}
}
