<?php declare(strict_types=1);

namespace Fortuneglobe\Types;

use Fortuneglobe\Types\Exceptions\InvalidIntValueException;
use Fortuneglobe\Types\Interfaces\RepresentsIntValue;

/**
 * Class IntType
 * @package Fortuneglobe\Types
 */
class IntType extends AbstractType implements RepresentsIntValue
{
	/** @var int */
	private $value;

	public function __construct( int $id )
	{
		$this->value = $id;
	}

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
		$intValue = (int)$string;

		if ( $string !== (string)$intValue )
		{
			throw (new InvalidIntValueException())->withString( $string );
		}

		return new static( $intValue );
	}
}
