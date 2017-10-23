<?php declare(strict_types=1);
/**
 * @author Christian Ramelow <christian.ramelow@fortuneglobe.com>
 */

namespace Fortuneglobe\Types\Tests\Unit;

use Fortuneglobe\Types\Interfaces\RepresentsScalarValue;
use Fortuneglobe\Types\Interfaces\RepresentsStringValue;
use Fortuneglobe\Types\StringType;
use PHPUnit\Framework\TestCase;

/**
 * Class IdentifierTest
 * @package Fortuneglobe\Types\Tests\Unit
 */
final class StringTypeTest extends TestCase
{
	public function testCanConstructStringIdentifier()
	{
		$type = new StringType( 'foobar' );
		$this->assertInstanceOf( RepresentsScalarValue::class, $type );
		$this->assertInstanceOf( RepresentsStringValue::class, $type );
		$this->assertInstanceOf( StringType::class, $type );
	}

	/**
	 * @param StringType $typeA
	 * @param StringType $typeB
	 *
	 * @dataProvider equalStringsProvider
	 */
	public function testCanCheckForEquality( StringType $typeA, StringType $typeB )
	{
		$this->assertTrue( $typeA->equals( $typeB ) );
		$this->assertTrue( $typeB->equals( $typeA ) );
	}

	public function equalStringsProvider()
	{
		return [
			[
				'typeA' => new StringType( 'foobar' ),
				'typeB' => new StringType( 'foobar' ),
			],
			[
				'typeA' => new StringType( 'Foobar' ),
				'typeB' => new StringType( 'Foobar' ),
			],
			[
				'typeA' => new StringType( '0.8' ),
				'typeB' => new StringType( '0.8' ),
			],
			[
				'typeA' => new StringType( '.8' ),
				'typeB' => new StringType( '.8' ),
			],
		];
	}

	/**
	 * @param StringType $typeA
	 * @param StringType $typeB
	 *
	 * @dataProvider notEqualStringsProvider
	 */
	public function testCanCheckThatStringTypesAreNotEqual(
		StringType $typeA,
		StringType $typeB
	)
	{
		$this->assertFalse( $typeA->equals( $typeB ) );
		$this->assertFalse( $typeB->equals( $typeA ) );
	}

	public function notEqualStringsProvider()
	{
		$extendedStringIdentifier = new class('foobar') extends StringType
		{
		};

		return [
			[
				'typeA' => new StringType( 'foobar' ),
				'typeB' => new StringType( 'Foobar' ),
			],
			[
				'typeA' => new StringType( '0.8' ),
				'typeB' => new StringType( '.8' ),
			],
			[
				'typeA' => new StringType( ' ' ),
				'typeB' => new StringType( '' ),
			],
			[
				'typeA' => new StringType( 'foobar' ),
				'typeB' => $extendedStringIdentifier,
			],
			[
				'typeA' => $extendedStringIdentifier,
				'typeB' => new class('Foobar') extends StringType
				{
				},
			],
		];
	}

	/**
	 * @param StringType $type
	 * @param string     $expectedString
	 *
	 * @dataProvider stringAsStringProvider
	 */
	public function testCanRepresentStringTypeAsString( StringType $type, string $expectedString )
	{
		$this->assertSame( $expectedString, $type->toString() );
		$this->assertSame( $expectedString, $type->__toString() );
		$this->assertSame( $expectedString, (string)$type );
	}

	public function stringAsStringProvider()
	{
		return [
			[
				'type'           => new StringType( 'foobar' ),
				'expectedString' => 'foobar',
			],
			[
				'type'           => new StringType( 'Foobar' ),
				'expectedString' => 'Foobar',
			],
			[
				'type'           => new StringType( '0.8' ),
				'expectedString' => '0.8',
			],
			[
				'type'           => new StringType( '.8' ),
				'expectedString' => '.8',
			],
		];
	}

	/**
	 * @param StringType $type
	 * @param string     $expectedJsonSerialized
	 *
	 * @dataProvider stringToJsonProvider
	 */
	public function testCanSerializeStringTypeAsJson( StringType $type, string $expectedJsonSerialized )
	{
		$this->assertSame( $expectedJsonSerialized, json_encode( $type ) );
	}

	public function stringToJsonProvider()
	{
		return [
			[
				'type'                   => new StringType( 'foobar' ),
				'expectedJsonSerialized' => '"foobar"',
			],
			[
				'type'                   => new StringType( 'Foobar' ),
				'expectedJsonSerialized' => '"Foobar"',
			],
			[
				'type'                   => new StringType( '0.8' ),
				'expectedJsonSerialized' => '"0.8"',
			],
			[
				'type'                   => new StringType( '.8' ),
				'expectedJsonSerialized' => '".8"',
			],
		];
	}
}
