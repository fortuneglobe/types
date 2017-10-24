<?php declare(strict_types=1);

namespace Fortuneglobe\Types;

use Fortuneglobe\Types\Exceptions\InvalidFloatValueException;
use Fortuneglobe\Types\Interfaces\RepresentsFloatValue;

/**
 * Class FloatType
 * @package Fortuneglobe\Types
 */
class FloatType extends AbstractType implements RepresentsFloatValue
{
	/** @var float */
	private $value;

	public function __construct( float $id )
	{
		$this->value = $id;
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
		$floatValue = (float)$string;

		if ( $string !== (string)$floatValue )
		{
			throw (new InvalidFloatValueException())->withString( $string );
		}

		return new static( $floatValue );
	}
}
