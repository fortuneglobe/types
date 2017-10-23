<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Exceptions;

/**
 * Class InvalidFloatValueException
 * @package Fortuneglobe\Types\Exceptions
 */
final class InvalidFloatValueException extends InvalidArgumentException
{
	/** @var string */
	private $string;

	public function getString() : string
	{
		return $this->string;
	}

	public function withString( string $string ) : InvalidFloatValueException
	{
		$this->message = sprintf( 'Invalid float value provided: %s', $string );
		$this->string  = $string;

		return $this;
	}
}
