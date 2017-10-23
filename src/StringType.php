<?php declare(strict_types=1);

namespace Fortuneglobe\Types;

use Fortuneglobe\Types\Interfaces\RepresentsStringValue;

/**
 * Interface StringType
 * @package Fortuneglobe\Types
 */
class StringType extends AbstractType implements RepresentsStringValue
{
	/** @var string */
	private $id;

	public function __construct( string $id )
	{
		$this->id = $id;
	}

	public function toString() : string
	{
		return $this->id;
	}
}
