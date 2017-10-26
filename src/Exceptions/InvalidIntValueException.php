<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Exceptions;

/**
 * Class InvalidIntValueException
 * @package Fortuneglobe\Types\Exceptions
 */
final class InvalidIntValueException extends InvalidArgumentException
{
	/** @var string */
	private $string;

	public function getString() : string
	{
		return $this->string;
	}

	public function withString( string $string ) : InvalidIntValueException
	{
		$this->message = sprintf( 'Invalid int value provided: %s', $string );
		$this->string  = $string;

		return $this;
	}
}
