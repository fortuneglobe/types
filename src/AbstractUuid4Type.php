<?php declare(strict_types=1);

namespace Fortuneglobe\Types;

use Fortuneglobe\Types\Exceptions\InvalidUuid4ValueException;
use Fortuneglobe\Types\Interfaces\RepresentsUuid4Value;

/**
 * Class AbstractUuid4Type
 * @package Fortuneglobe\Types
 */
abstract class AbstractUuid4Type extends AbstractStringType implements RepresentsUuid4Value
{
	private const NIL          = '00000000-0000-0000-0000-000000000000';

	private const UUID_PATTERN = '^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$';

	final protected function guardValueIsValid( string $value ) : void
	{
		if ( self::NIL === $value )
		{
			return;
		}

		if ( preg_match( '#' . self::UUID_PATTERN . '#i', $value ) )
		{
			return;
		}

		throw (new InvalidUuid4ValueException())->withUuid4( $value );
	}

	public static function generate() : RepresentsUuid4Value
	{
		$data = random_bytes( 16 );

		$data[6] = chr( ord( $data[6] ) & 0x0f | 0x40 );
		$data[8] = chr( ord( $data[8] ) & 0x3f | 0x80 );

		$uuid = vsprintf( '%s%s-%s-%s-%s-%s%s%s', str_split( bin2hex( $data ), 4 ) );

		return new static( $uuid );
	}
}
