<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Interfaces;

/**
 * Interface RepresentsIntValue
 * @package Fortuneglobe\Types\Interfaces
 */
interface RepresentsIntValue extends RepresentsScalarValue
{
	public function toInt() : int;

	public static function fromString( string $string ) : RepresentsIntValue;
}
