<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Traits;

use Fortuneglobe\Types\Exceptions\ValidationException;
use Fortuneglobe\Types\Interfaces\RepresentsFloatType;
use Fortuneglobe\Types\Interfaces\RepresentsIntType;

trait RepresentingFloatArrayType
{
	/** @var RepresentsFloatType[] */
	private array $floatTypes;

	/**
	 * @param RepresentsFloatType[] $floatTypes
	 */
	public function __construct( array $floatTypes = [] )
	{
		$this->floatTypes = $floatTypes;
	}

	/**
	 * @return float[]
	 */
	public function toArray(): array
	{
		$floats = [];
		foreach ( $this->floatTypes as $floatType )
		{
			$floats[] = $floatType->toFloat();
		}

		return $floats;
	}

	public function current():RepresentsFloatType
	{
		return current( $this->floatTypes );
	}

	public function next(): void
	{
		next( $this->floatTypes );
	}

	public function key()
	{
		return key( $this->floatTypes );
	}

	public function valid(): bool
	{
		return isset( $this->floatTypes[ $this->key() ] );
	}

	public function rewind(): void
	{
		reset( $this->floatTypes );
	}

	public function offsetExists( $offset ): bool
	{
		return isset( $this->floatTypes[ (string)$offset ] );
	}

	public function offsetGet( $offset ): RepresentsFloatType
	{
		if ( !isset( $this->floatTypes[ (string)$offset ] ) )
		{
			throw new \LogicException( 'Key not found in array: ' . $offset );
		}

		return $this->floatTypes[ (string)$offset ];
	}

	public function offsetSet( $offset, $value ): void
	{
		if ( !($value instanceof RepresentsFloatType) )
		{
			throw new ValidationException( 'Not implementing ' . RepresentsFloatType::class . ': ' . print_r( $value, true ) );
		}

		$this->floatTypes[ (string)$offset ] = $value;
	}

	public function offsetUnset( $offset )
	{
		unset( $this->floatTypes[ (string)$offset ] );
	}

	public function count(): int
	{
		return count( $this->floatTypes );
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
