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
	private $id;

	public function __construct( int $id )
	{
		$this->id = $id;
	}

	public function toString() : string
	{
		return (string)$this->id;
	}

	public function toInt() : int
	{
		return $this->id;
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
