<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit;

use Fortuneglobe\Types\Exceptions\InvalidIntValueException;
use Fortuneglobe\Types\Interfaces\RepresentsIntValue;
use Fortuneglobe\Types\Interfaces\RepresentsScalarValue;
use Fortuneglobe\Types\IntType;
use PHPUnit\Framework\TestCase;

/**
 * Class IntTypeTest
 * @package Fortuneglobe\Types\Tests\Unit
 */
final class IntTypeTest extends TestCase
{
	public function testCanConstuctIntType() : void
	{
		$type = new IntType( 1234 );

		$this->assertInstanceOf( RepresentsScalarValue::class, $type );
		$this->assertInstanceOf( RepresentsIntValue::class, $type );
		$this->assertInstanceOf( IntType::class, $type );
	}

	/**
	 * @param IntType $typeA
	 * @param IntType $typeB
	 *
	 * @dataProvider equalIntsProvider
	 */
	public function testCanCheckForEquality( IntType $typeA, IntType $typeB )
	{
		$this->assertTrue( $typeA->equals( $typeB ) );
		$this->assertTrue( $typeB->equals( $typeA ) );
	}

	public function equalIntsProvider()
	{
		return [
			[
				'typeA' => new IntType( 1 ),
				'typeB' => new IntType( 1 ),
			],
			[
				'typeA' => new IntType( 0 ),
				'typeB' => new IntType( 0 ),
			],
			[
				'typeA' => new IntType( -1 ),
				'typeB' => new IntType( -1 ),
			],
			[
				'typeA' => new IntType( PHP_INT_MAX ),
				'typeB' => new IntType( PHP_INT_MAX ),
			],
			[
				'typeA' => new IntType( PHP_INT_MIN ),
				'typeB' => new IntType( PHP_INT_MIN ),
			],
		];
	}

	/**
	 * @param IntType $typeA
	 * @param IntType $typeB
	 *
	 * @dataProvider notEqualIntsProvider
	 */
	public function testCanCheckThatIntTypesAreNotEqual( IntType $typeA, IntType $typeB )
	{
		$this->assertFalse( $typeA->equals( $typeB ) );
		$this->assertFalse( $typeB->equals( $typeA ) );
	}

	public function notEqualIntsProvider()
	{
		return [
			[
				'typeA' => new IntType( 1 ),
				'typeB' => new IntType( -1 ),
			],
			[
				'typeA' => new IntType( 0 ),
				'typeB' => new IntType( PHP_INT_MAX ),
			],
			[
				'typeA' => new IntType( 0 ),
				'typeB' => new IntType( PHP_INT_MIN ),
			],
			[
				'typeA' => new IntType( PHP_INT_MIN ),
				'typeB' => new IntType( PHP_INT_MAX ),
			],
			[
				'typeA' => new IntType( 0 ),
				'typeB' => new class(0) extends IntType
				{
				},
			],
		];
	}

	/**
	 * @param IntType $type
	 * @param string  $expectedString
	 *
	 * @dataProvider intAsStringProvider
	 */
	public function testCanRepresentIntTypeAsString( IntType $type, string $expectedString )
	{
		$this->assertSame( $expectedString, $type->toString() );
		$this->assertSame( $expectedString, $type->__toString() );
		$this->assertSame( $expectedString, (string)$type );
	}

	public function intAsStringProvider()
	{
		return [
			[
				'type'           => new IntType( 1 ),
				'expectedString' => '1',
			],
			[
				'type'           => new IntType( 0 ),
				'expectedString' => '0',
			],
			[
				'type'           => new IntType( -1 ),
				'expectedString' => '-1',
			],
			[
				'type'           => new IntType( PHP_INT_MAX ),
				'expectedString' => (string)PHP_INT_MAX,
			],
			[
				'type'           => new IntType( PHP_INT_MIN ),
				'expectedString' => (string)PHP_INT_MIN,
			],
		];
	}

	public function testCanGetIntValue() : void
	{
		$type = new IntType( 1234 );

		$this->assertSame( 1234, $type->toInt() );
	}

	/**
	 * @param int    $value
	 * @param string $expectedJsonSerialized
	 *
	 * @dataProvider intAsJsonDataProvider
	 */
	public function testCanSerializeIntTypeAsJson( int $value, string $expectedJsonSerialized )
	{
		$type = new IntType( $value );

		$this->assertSame( $expectedJsonSerialized, json_encode( $type ) );
	}

	public function intAsJsonDataProvider()
	{
		return [
			[
				'value'                  => 1,
				'expectedJsonSerialized' => '"1"',
			],
			[
				'value'                  => -1,
				'expectedJsonSerialized' => '"-1"',
			],
			[
				'value'                  => 0,
				'expectedJsonSerialized' => '"0"',
			],
		];
	}

	/**
	 * @param string $string
	 * @param int    $expectedInt
	 *
	 * @dataProvider stringIntValuesProvider
	 */
	public function testCanReconstructIntTypeFromString( string $string, int $expectedInt ) : void
	{
		$type = IntType::fromString( $string );

		$this->assertSame( $expectedInt, $type->toInt() );
		$this->assertTrue( (new IntType( $expectedInt ))->equals( $type ) );
	}

	public function stringIntValuesProvider() : array
	{
		return [
			[
				'string'      => '123',
				'expectedInt' => 123,
			],
			[
				'string'      => (string)PHP_INT_MAX,
				'expectedInt' => PHP_INT_MAX,
			],
			[
				'string'      => (string)PHP_INT_MIN,
				'expectedInt' => PHP_INT_MIN,
			],
		];
	}

	/**
	 * @param string $string
	 *
	 * @dataProvider stringInvalidIntValuesProvider
	 */
	public function testExpectExceptionIfStringIsNotAValidIntValue( string $string ) : void
	{
		try
		{
			IntType::fromString( $string );
		}
		catch ( InvalidIntValueException $e )
		{
			$this->assertSame( $string, $e->getString() );
			$this->assertSame( 'Invalid int value provided: ' . $string, $e->getMessage() );
		}
	}

	public function stringInvalidIntValuesProvider() : array
	{
		return [
			[
				'string' => 'f0.42',
			],
			[
				'string' => 'ABC',
			],
			[
				'string' => '-1.55c',
			],
			[
				'string' => '-1,33',
			],
		];
	}
}
