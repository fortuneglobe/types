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
	private $id;

	public function __construct( float $id )
	{
		$this->id = $id;
	}

	public function toString() : string
	{
		return (string)$this->id;
	}

	public function toFloat() : float
	{
		return $this->id;
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
