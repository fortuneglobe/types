<?php declare(strict_types=1);

namespace Fortuneglobe\Types;

use Fortuneglobe\Types\Exceptions\InvalidIntValueException;
use Fortuneglobe\Types\Interfaces\RepresentsIntValue;

/**
 * Class AbstractIntType
 * @package Fortuneglobe\Types
 */
abstract class AbstractIntType extends AbstractType implements RepresentsIntValue
{
	/** @var int */
	private $value;

	public function __construct( int $value )
	{
		$this->guardValueIsValid( $value );

		$this->value = $value;
	}

	abstract protected function guardValueIsValid( int $value ) : void;

	public function toString() : string
	{
		return (string)$this->value;
	}

	public function toInt() : int
	{
		return $this->value;
	}

	public function jsonSerialize() : int
	{
		return $this->value;
	}

	public static function fromString( string $string ) : RepresentsIntValue
	{
		if ( 0 === preg_match( '#^[-+]?\d+$#', $string ) )
		{
			throw (new InvalidIntValueException())->withString( $string );
		}

		$floatValue = (float)$string;
		if ( PHP_INT_MAX < $floatValue || PHP_INT_MIN > $floatValue )
		{
			throw (new InvalidIntValueException())->withString( $string );
		}

		return new static( (int)$string );
	}
}
