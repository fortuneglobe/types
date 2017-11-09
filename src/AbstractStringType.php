<?php declare(strict_types=1);

namespace Fortuneglobe\Types;

use Fortuneglobe\Types\Interfaces\RepresentsStringValue;

/**
 * Interface AbstractStringType
 * @package Fortuneglobe\Types
 */
abstract class AbstractStringType extends AbstractType implements RepresentsStringValue
{
	/** @var string */
	private $value;

	public function __construct( string $value )
	{
		$this->guardValueIsValid( $value );

		$this->value = $value;
	}

	abstract protected function guardValueIsValid( string $value ) : void;

	public function jsonSerialize() : string
	{
		return $this->value;
	}

	public function toString() : string
	{
		return $this->value;
	}
}
