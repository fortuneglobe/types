<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit;

use Fortuneglobe\Types\Exceptions\InvalidFloatValueException;
use Fortuneglobe\Types\FloatType;
use Fortuneglobe\Types\Interfaces\RepresentsFloatValue;
use Fortuneglobe\Types\Interfaces\RepresentsScalarValue;
use PHPUnit\Framework\TestCase;

/**
 * Class FloatTypeTest
 * @package Fortuneglobe\Types\Tests\Unit
 */
final class FloatTypeTest extends TestCase
{
	public function testCanConstructFloatType() : void
	{
		$type = new FloatType( 0.42 );

		$this->assertInstanceOf( RepresentsScalarValue::class, $type );
		$this->assertInstanceOf( RepresentsFloatValue::class, $type );
		$this->assertInstanceOf( FloatType::class, $type );
	}

	/**
	 * @param FloatType $typeA
	 * @param FloatType $typeB
	 *
	 * @dataProvider equalFloatsProvider
	 */
	public function testCanCheckForEquality( FloatType $typeA, FloatType $typeB )
	{
		$this->assertTrue( $typeA->equals( $typeB ) );
		$this->assertTrue( $typeB->equals( $typeA ) );
	}

	public function equalFloatsProvider()
	{
		return [
			[
				'typeA' => new FloatType( 1 ),
				'typeB' => new FloatType( 1.0 ),
			],
			[
				'typeA' => new FloatType( 0 ),
				'typeB' => new FloatType( 0.0 ),
			],
			[
				'typeA' => new FloatType( -1 ),
				'typeB' => new FloatType( -1.0 ),
			],
			[
				'typeA' => new FloatType( 987.123 ),
				'typeB' => new FloatType( 987.123 ),
			],
		];
	}

	/**
	 * @param FloatType $typeA
	 * @param FloatType $typeB
	 *
	 * @dataProvider notEqualFloatsProvider
	 */
	public function testCanCheckThatFloatTypesAreNotEqual( FloatType $typeA, FloatType $typeB )
	{
		$this->assertFalse( $typeA->equals( $typeB ) );
		$this->assertFalse( $typeB->equals( $typeA ) );
	}

	public function notEqualFloatsProvider()
	{
		return [
			[
				'typeA' => new FloatType( 1.0 ),
				'typeB' => new FloatType( -1.0 ),
			],
			[
				'typeA' => new FloatType( 0.0 ),
				'typeB' => new FloatType( 0.1 ),
			],
			[
				'typeA' => new FloatType( -1.0 ),
				'typeB' => new FloatType( -1.1 ),
			],
			[
				'typeA' => new FloatType( -1.5 ),
				'typeB' => new class(-1.5) extends FloatType
				{
				},
			],
		];
	}

	/**
	 * @param FloatType $type
	 * @param string    $expectedString
	 *
	 * @dataProvider floatTypeAsStringProvider
	 */
	public function testCanRepresentFloatTypeAsString( FloatType $type, string $expectedString )
	{
		$this->assertSame( $expectedString, $type->toString() );
		$this->assertSame( $expectedString, $type->__toString() );
		$this->assertSame( $expectedString, (string)$type );
	}

	public function floatTypeAsStringProvider()
	{
		return [
			[
				'type'           => new FloatType( 1 ),
				'expectedString' => '1',
			],
			[
				'type'           => new FloatType( 0 ),
				'expectedString' => '0',
			],
			[
				'type'           => new FloatType( -1 ),
				'expectedString' => '-1',
			],
			[
				'type'           => new FloatType( 1.0 ),
				'expectedString' => '1',
			],
			[
				'type'           => new FloatType( 0.0 ),
				'expectedString' => '0',
			],
			[
				'type'           => new FloatType( 1.0 ),
				'expectedString' => '1',
			],
			[
				'type'           => new FloatType( 0.1 ),
				'expectedString' => '0.1',
			],
			[
				'type'           => new FloatType( 1.1 ),
				'expectedString' => '1.1',
			],
			[
				'type'           => new FloatType( -1.1 ),
				'expectedString' => '-1.1',
			],
		];
	}

	/**
	 * @param float  $value
	 * @param string $expectedJson
	 *
	 * @dataProvider floatTypeAsJsonProvider
	 */
	public function testCanSerializeFloatTypeAsJson( float $value, string $expectedJson )
	{
		$type = new FloatType( $value );

		$this->assertSame( $expectedJson, json_encode( $type ) );
	}

	public function floatTypeAsJsonProvider()
	{
		return [
			[
				'value'        => 1.23,
				'expectedJson' => '1.23',
			],
			[
				'value'        => -1.1,
				'expectedJson' => '-1.1',
			],
			[
				'value'        => 0.5,
				'expectedJson' => '0.5',
			],
		];
	}

	public function testCanGetFloatValue() : void
	{
		$type = new FloatType( 0.42 );

		$this->assertSame( 0.42, $type->toFloat() );
	}

	/**
	 * @param string $string
	 * @param float  $expectedFloat
	 *
	 * @dataProvider stringFloatValuesProvider
	 */
	public function testCanReconstructFloatTypeFromString( string $string, float $expectedFloat ) : void
	{
		$type = FloatType::fromString( $string );

		$this->assertSame( $expectedFloat, $type->toFloat() );
		$this->assertTrue( (new FloatType( $expectedFloat ))->equals( $type ) );
	}

	public function stringFloatValuesProvider() : array
	{
		return [
			[
				'string'        => '0.42',
				'expectedFloat' => 0.42,
			],
			[
				'string'        => '0',
				'expectedFloat' => 0,
			],
			[
				'string'        => '-1.23',
				'expectedFloat' => -1.23,
			],
		];
	}

	/**
	 * @param string $string
	 *
	 * @dataProvider stringInvalidFloatValuesProvider
	 */
	public function testExpectExceptionIfStringIsNotAValidFloatValue( string $string ) : void
	{
		try
		{
			FloatType::fromString( $string );
		}
		catch ( InvalidFloatValueException $e )
		{
			$this->assertSame( $string, $e->getString() );
			$this->assertSame( 'Invalid float value provided: ' . $string, $e->getMessage() );
		}
	}

	public function stringInvalidFloatValuesProvider() : array
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
