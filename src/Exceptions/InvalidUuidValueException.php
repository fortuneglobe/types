<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Exceptions;

/**
 * Class InvalidUuidValueException
 * @package Fortuneglobe\Types\Exceptions
 */
final class InvalidUuidValueException extends InvalidArgumentException
{
	/** @var string */
	private $uuid;

	public function getUuid() : string
	{
		return $this->uuid;
	}

	public function withUuid( string $uuid ) : self
	{
		$this->message = sprintf( 'Invalid UUID provided: %s', $uuid );
		$this->uuid    = $uuid;

		return $this;
	}
}
