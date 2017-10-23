<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Interfaces;

/**
 * Interface RepresentsScalarValue
 * @package Fortuneglobe\Types\Interfaces
 */
interface RepresentsScalarValue extends \JsonSerializable
{
	public function __toString() : string;

	public function toString() : string;

	public function equals( RepresentsScalarValue $other ) : bool;
}
