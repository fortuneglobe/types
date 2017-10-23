<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Exceptions;

/**
 * Class InvalidUUIDValueException
 * @package Fortuneglobe\Types\Exceptions
 */
final class InvalidUUIDValueException extends InvalidArgumentException
{
	/** @var string */
	private $uuid;

	public function getUUID() : string
	{
		return $this->uuid;
	}

	public function withUUID( string $uuid ) : self
	{
		$this->message = sprintf( 'Invalid UUID provided: %s', $uuid );
		$this->uuid    = $uuid;

		return $this;
	}
}
