<?php declare(strict_types=1);

namespace Fortuneglobe\Types;

use Fortuneglobe\Types\Interfaces\RepresentsScalarValue;

/**
 * Class AbstractType
 * @package Fortuneglobe\Types
 */
abstract class AbstractType
{
	final public function __toString() : string
	{
		return $this->toString();
	}

	abstract public function toString() : string;

	abstract public function jsonSerialize();

	public function equals( RepresentsScalarValue $other ) : bool
	{
		return ($this->toString() === $other->toString() && static::class === get_class( $other ));
	}
}
