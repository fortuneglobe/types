<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit;

use Fortuneglobe\Types\AbstractIntType;
use Fortuneglobe\Types\Exceptions\InvalidIntValueException;
use Fortuneglobe\Types\Interfaces\RepresentsIntValue;
use Fortuneglobe\Types\Interfaces\RepresentsScalarValue;
use Fortuneglobe\Types\Tests\Unit\Fixtures\TestInt;
use PHPUnit\Framework\TestCase;

/**
 * Class IntTypeTest
 * @package Fortuneglobe\Types\Tests\Unit
 */
final class AbstractIntTypeTest extends TestCase
{
	public function testCanConstructIntType() : void
	{
		$type = new TestInt( 1234 );

		$this->assertInstanceOf( RepresentsScalarValue::class, $type );
		$this->assertInstanceOf( RepresentsIntValue::class, $type );
		$this->assertInstanceOf( AbstractIntType::class, $type );
	}

	/**
	 * @param RepresentsIntValue $typeA
	 * @param RepresentsIntValue $typeB
	 *
	 * @dataProvider equalIntsProvider
	 */
	public function testCanCheckForEquality( RepresentsIntValue $typeA, RepresentsIntValue $typeB )
	{
		$this->assertTrue( $typeA->equals( $typeB ) );
		$this->assertTrue( $typeB->equals( $typeA ) );
	}

	public function equalIntsProvider()
	{
		return [
			[
				'typeA' => new TestInt( 1 ),
				'typeB' => new TestInt( 1 ),
			],
			[
				'typeA' => new TestInt( 0 ),
				'typeB' => new TestInt( 0 ),
			],
			[
				'typeA' => new TestInt( -1 ),
				'typeB' => new TestInt( -1 ),
			],
			[
				'typeA' => new TestInt( PHP_INT_MAX ),
				'typeB' => new TestInt( PHP_INT_MAX ),
			],
			[
				'typeA' => new TestInt( PHP_INT_MIN ),
				'typeB' => new TestInt( PHP_INT_MIN ),
			],
		];
	}

	/**
	 * @param RepresentsIntValue $typeA
	 * @param RepresentsIntValue $typeB
	 *
	 * @dataProvider notEqualIntsProvider
	 */
	public function testCanCheckThatIntTypesAreNotEqual( RepresentsIntValue $typeA, RepresentsIntValue $typeB )
	{
		$this->assertFalse( $typeA->equals( $typeB ) );
		$this->assertFalse( $typeB->equals( $typeA ) );
	}

	public function notEqualIntsProvider()
	{
		return [
			[
				'typeA' => new TestInt( 1 ),
				'typeB' => new TestInt( -1 ),
			],
			[
				'typeA' => new TestInt( 0 ),
				'typeB' => new TestInt( PHP_INT_MAX ),
			],
			[
				'typeA' => new TestInt( 0 ),
				'typeB' => new TestInt( PHP_INT_MIN ),
			],
			[
				'typeA' => new TestInt( PHP_INT_MIN ),
				'typeB' => new TestInt( PHP_INT_MAX ),
			],
			[
				'typeA' => new TestInt( 0 ),
				'typeB' => new class(0) extends AbstractIntType
				{
					protected function guardValueIsValid( int $value )
					{
						// TODO: Implement guardValueIsValid() method.
					}
				},
			],
		];
	}

	/**
	 * @param RepresentsIntValue $type
	 * @param string             $expectedString
	 *
	 * @dataProvider intAsStringProvider
	 */
	public function testCanRepresentIntTypeAsString( RepresentsIntValue $type, string $expectedString )
	{
		$this->assertSame( $expectedString, $type->toString() );
		$this->assertSame( $expectedString, $type->__toString() );
		$this->assertSame( $expectedString, (string)$type );
	}

	public function intAsStringProvider()
	{
		return [
			[
				'type'           => new TestInt( 1 ),
				'expectedString' => '1',
			],
			[
				'type'           => new TestInt( 0 ),
				'expectedString' => '0',
			],
			[
				'type'           => new TestInt( -1 ),
				'expectedString' => '-1',
			],
			[
				'type'           => new TestInt( PHP_INT_MAX ),
				'expectedString' => (string)PHP_INT_MAX,
			],
			[
				'type'           => new TestInt( PHP_INT_MIN ),
				'expectedString' => (string)PHP_INT_MIN,
			],
		];
	}

	public function testCanGetIntValue() : void
	{
		$type = new TestInt( 1234 );

		$this->assertSame( 1234, $type->toInt() );
	}

	/**
	 * @param int    $value
	 * @param string $expectedJson
	 *
	 * @dataProvider intAsJsonDataProvider
	 */
	public function testCanSerializeIntTypeAsJson( int $value, string $expectedJson )
	{
		$type = new TestInt( $value );

		$this->assertSame( $expectedJson, json_encode( $type ) );
	}

	public function intAsJsonDataProvider()
	{
		return [
			[
				'value'        => 1,
				'expectedJson' => '1',
			],
			[
				'value'        => -1,
				'expectedJson' => '-1',
			],
			[
				'value'        => 0,
				'expectedJson' => '0',
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
		$type = TestInt::fromString( $string );

		$this->assertSame( $expectedInt, $type->toInt() );
		$this->assertTrue( (new TestInt( $expectedInt ))->equals( $type ) );
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
			[
				'string'      => '+42',
				'expectedInt' => 42,
			],
			[
				'string'      => '-42',
				'expectedInt' => -42,
			],
			[
				'string'      => '0042',
				'expectedInt' => 42,
			],
			[
				'string'      => '-0042',
				'expectedInt' => -42,
			],
			[
				'string'      => '+0042',
				'expectedInt' => 42,
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
			TestInt::fromString( $string );
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
			[
				'string' => ((string)PHP_INT_MAX) . '1',
			],
			[
				'string' => ((string)PHP_INT_MIN) . '1',
			],
		];
	}
}
