<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Exceptions;

/**
 * Class InvalidUuid4ValueException
 * @package Fortuneglobe\Types\Exceptions
 */
final class InvalidUuid4ValueException extends InvalidArgumentException
{
	/** @var string */
	private $uuid4;

	public function getUuid4() : string
	{
		return $this->uuid4;
	}

	public function withUuid4( string $uuid4 ) : self
	{
		$this->message = sprintf( 'Invalid UUID provided: %s', $uuid4 );
		$this->uuid4   = $uuid4;

		return $this;
	}
}
