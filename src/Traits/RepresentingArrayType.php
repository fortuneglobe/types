<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Traits;

trait RepresentingArrayType
{
	private array $value;

	public function __construct( array $value )
	{
		$this->value = $value;
	}

	public function toArray(): array
	{
		return $this->value;
	}
}
