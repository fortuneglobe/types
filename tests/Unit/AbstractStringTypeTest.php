<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit;

use Fortuneglobe\Types\AbstractStringType;
use Fortuneglobe\Types\Exceptions\InvalidArgumentException;
use Fortuneglobe\Types\Interfaces\RepresentsScalarValue;
use Fortuneglobe\Types\Interfaces\RepresentsStringValue;
use Fortuneglobe\Types\Tests\Unit\Fixtures\TestNonEmptyStringType;
use Fortuneglobe\Types\Tests\Unit\Fixtures\TestString;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractStringTypeTest
 * @package Fortuneglobe\Types\Tests\Unit
 */
final class AbstractStringTypeTest extends TestCase
{
	public function testCanConstructStringIdentifier()
	{
		$type = new TestString( 'foobar' );
		$this->assertInstanceOf( RepresentsScalarValue::class, $type );
		$this->assertInstanceOf( RepresentsStringValue::class, $type );
		$this->assertInstanceOf( AbstractStringType::class, $type );
	}

	/**
	 * @param RepresentsStringValue $typeA
	 * @param RepresentsStringValue $typeB
	 *
	 * @dataProvider equalStringsProvider
	 */
	public function testCanCheckForEquality( RepresentsStringValue $typeA, RepresentsStringValue $typeB )
	{
		$this->assertTrue( $typeA->equals( $typeB ) );
		$this->assertTrue( $typeB->equals( $typeA ) );
	}

	public function equalStringsProvider()
	{
		return [
			[
				'typeA' => new TestString( 'foobar' ),
				'typeB' => new TestString( 'foobar' ),
			],
			[
				'typeA' => new TestString( 'Foobar' ),
				'typeB' => new TestString( 'Foobar' ),
			],
			[
				'typeA' => new TestString( '0.8' ),
				'typeB' => new TestString( '0.8' ),
			],
			[
				'typeA' => new TestString( '.8' ),
				'typeB' => new TestString( '.8' ),
			],
		];
	}

	/**
	 * @param RepresentsStringValue $typeA
	 * @param RepresentsStringValue $typeB
	 *
	 * @dataProvider notEqualStringsProvider
	 */
	public function testCanCheckThatStringTypesAreNotEqual( RepresentsStringValue $typeA, RepresentsStringValue $typeB )
	{
		$this->assertFalse( $typeA->equals( $typeB ) );
		$this->assertFalse( $typeB->equals( $typeA ) );
	}

	public function notEqualStringsProvider()
	{
		$extendedStringIdentifier = new class('foobar') extends AbstractStringType
		{
			protected function guardValueIsValid( string $value ) : void
			{
				// TODO: Implement guardValueIsValid() method.
			}
		};

		return [
			[
				'typeA' => new TestString( 'foobar' ),
				'typeB' => new TestString( 'Foobar' ),
			],
			[
				'typeA' => new TestString( '0.8' ),
				'typeB' => new TestString( '.8' ),
			],
			[
				'typeA' => new TestString( ' ' ),
				'typeB' => new TestString( '' ),
			],
			[
				'typeA' => new TestString( 'foobar' ),
				'typeB' => $extendedStringIdentifier,
			],
			[
				'typeA' => $extendedStringIdentifier,
				'typeB' => new class('Foobar') extends AbstractStringType
				{
					protected function guardValueIsValid( string $value ) : void
					{
						// TODO: Implement guardValueIsValid() method.
					}
				},
			],
		];
	}

	/**
	 * @param RepresentsStringValue $type
	 * @param string                $expectedString
	 *
	 * @dataProvider stringAsStringProvider
	 */
	public function testCanRepresentStringTypeAsString( RepresentsStringValue $type, string $expectedString )
	{
		$this->assertSame( $expectedString, $type->toString() );
		$this->assertSame( $expectedString, $type->__toString() );
		$this->assertSame( $expectedString, (string)$type );
	}

	public function stringAsStringProvider()
	{
		return [
			[
				'type'           => new TestString( 'foobar' ),
				'expectedString' => 'foobar',
			],
			[
				'type'           => new TestString( 'Foobar' ),
				'expectedString' => 'Foobar',
			],
			[
				'type'           => new TestString( '0.8' ),
				'expectedString' => '0.8',
			],
			[
				'type'           => new TestString( '.8' ),
				'expectedString' => '.8',
			],
		];
	}

	/**
	 * @param RepresentsStringValue $type
	 * @param string                $expectedJson
	 *
	 * @dataProvider stringToJsonProvider
	 */
	public function testCanSerializeStringTypeAsJson( RepresentsStringValue $type, string $expectedJson )
	{
		$this->assertSame( $expectedJson, json_encode( $type ) );
	}

	public function stringToJsonProvider()
	{
		return [
			[
				'type'         => new TestString( 'foobar' ),
				'expectedJson' => '"foobar"',
			],
			[
				'type'         => new TestString( 'Foobar' ),
				'expectedJson' => '"Foobar"',
			],
			[
				'type'         => new TestString( '0.8' ),
				'expectedJson' => '"0.8"',
			],
			[
				'type'         => new TestString( '.8' ),
				'expectedJson' => '".8"',
			],
		];
	}

	public function testGuardMethodIsCalledOnConstruction() : void
	{
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'String cannot be empty.' );

		new TestNonEmptyStringType( '' );
	}
}
