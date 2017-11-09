<?php declare(strict_types=1);

namespace Fortuneglobe\Types;

use Fortuneglobe\Types\Exceptions\InvalidFloatValueException;
use Fortuneglobe\Types\Interfaces\RepresentsFloatValue;

/**
 * Class AbstractFloatType
 * @package Fortuneglobe\Types
 */
abstract class AbstractFloatType extends AbstractType implements RepresentsFloatValue
{
	/** @var float */
	private $value;

	public function __construct( float $value )
	{
		$this->value = $value;
	}

	public function toString() : string
	{
		return (string)$this->value;
	}

	public function toFloat() : float
	{
		return $this->value;
	}

	public function jsonSerialize() : float
	{
		return $this->value;
	}

	public static function fromString( string $string ) : RepresentsFloatValue
	{
		if ( false === is_numeric( $string ) )
		{
			throw (new InvalidFloatValueException())->withString( $string );
		}

		return new static( (float)$string );
	}
}
