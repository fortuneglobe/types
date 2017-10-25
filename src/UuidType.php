<?php declare(strict_types=1);

namespace Fortuneglobe\Types;

use Fortuneglobe\Types\Exceptions\InvalidUuidValueException;
use Fortuneglobe\Types\Interfaces\RepresentsUuidValue;

/**
 * Class UuidType
 * @package Fortuneglobe\Types
 */
abstract class UuidType extends StringType implements RepresentsUuidValue
{
	private const NIL          = '00000000-0000-0000-0000-000000000000';

	private const UUID_PATTERN = '^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$';

	public function __construct( string $uuid )
	{
		$this->guardUuidIsValid( $uuid );

		parent::__construct( $uuid );
	}

	private function guardUuidIsValid( string $uuid ) : void
	{
		if ( self::NIL === $uuid )
		{
			return;
		}

		if ( preg_match( '#' . self::UUID_PATTERN . '#', $uuid ) )
		{
			return;
		}

		throw (new InvalidUuidValueException())->withUuid( $uuid );
	}

	public static function generate() : RepresentsUuidValue
	{
		$data = random_bytes( 16 );

		$data[6] = chr( ord( $data[6] ) & 0x0f | 0x40 );
		$data[8] = chr( ord( $data[8] ) & 0x3f | 0x80 );

		$uuid = vsprintf( '%s%s-%s-%s-%s-%s%s%s', str_split( bin2hex( $data ), 4 ) );

		return new static( $uuid );
	}
}
