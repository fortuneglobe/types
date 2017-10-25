<?php declare(strict_types=1);

namespace Fortuneglobe\Types;

use Fortuneglobe\Types\Interfaces\RepresentsStringValue;

/**
 * Interface StringType
 * @package Fortuneglobe\Types
 */
abstract class StringType extends AbstractType implements RepresentsStringValue
{
	/** @var string */
	private $value;

	public function __construct( string $id )
	{
		$this->value = $id;
	}

	public function jsonSerialize() : string
	{
		return $this->value;
	}

	public function toString() : string
	{
		return $this->value;
	}
}
