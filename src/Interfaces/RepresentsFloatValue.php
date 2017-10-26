<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Interfaces;

/**
 * Interface RepresentsFloatValue
 * @package Fortuneglobe\Types\Interfaces
 */
interface RepresentsFloatValue extends RepresentsScalarValue
{
	public function toFloat() : float;

	public static function fromString( string $string ) : RepresentsFloatValue;
}
