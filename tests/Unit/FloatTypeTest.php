<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit;

use Fortuneglobe\Types\AbstractFloatType;
use Fortuneglobe\Types\Exceptions\ValidationException;
use Fortuneglobe\Types\Interfaces\RepresentsFloatType;
use Fortuneglobe\Types\Interfaces\RepresentsIntType;
use Fortuneglobe\Types\Tests\Unit\Samples\AnotherFloatType;
use Fortuneglobe\Types\Tests\Unit\Samples\AnyFloatType;
use Fortuneglobe\Types\Tests\Unit\Samples\JustAnIntType;
use Fortuneglobe\Types\Tests\Unit\Samples\NoZeroFloatType;
use Fortuneglobe\Types\Traits\RepresentingFloatType;
use PHPUnit\Framework\TestCase;

class FloatTypeTest extends TestCase
{
	public function ToStringDataProvider(): array
	{
		return [
			[ new AnyFloatType( 1 ), '1' ],
			[ new AnyFloatType( -1 ), '-1' ],
			[ new AnyFloatType( 0 ), '0' ],
			[ new AnyFloatType( 0.5 ), '0.5' ],
			[ new AnyFloatType( -1.75 ), '-1.75' ],
		];
	}

	/**
	 * @dataProvider ToStringDataProvider
	 *
	 * @param RepresentsFloatType $floatType
	 * @param string              $expectedString
	 */
	public function testToString( RepresentsFloatType $floatType, string $expectedString ): void
	{
		self::assertSame( $expectedString, (string)$floatType );
	}

	public function ToFloatDataProvider(): array
	{
		return [
			[ new AnyFloatType( 1 ), 1.0 ],
			[ new AnyFloatType( -1 ), -1.0 ],
			[ new AnyFloatType( 0 ), 0.0 ],
			[ new AnyFloatType( 0.5 ), 0.5 ],
			[ new AnyFloatType( -1.75 ), -1.75 ],
		];
	}

	/**
	 * @dataProvider ToFloatDataProvider
	 *
	 * @param RepresentsFloatType $floatType
	 * @param float               $expectedFloat
	 */
	public function testToFloat( RepresentsFloatType $floatType, float $expectedFloat ): void
	{
		self::assertSame( $expectedFloat, $floatType->toFloat() );
	}

	public function testInvalidValueThrowsException(): void
	{
		$this->expectException( ValidationException::class );
		$this->expectExceptionMessage( 'Invalid NoZeroFloatType: 0' );

		new NoZeroFloatType( 0 );
	}

	public function testFromFloatType(): void
	{
		self::assertEquals( new AnyFloatType( 15.5 ), AnyFloatType::fromFloatType( new AnotherFloatType( 15.5 ) ) );
	}

	public function testIfSameClassWithSameValueEquals()
	{
		self::assertTrue( (new AnyFloatType( 22.5 ))->equals( new AnyFloatType( 22.5 ) ) );
	}

	public function testIfSameClassWithAnotherValueIsNotEqual()
	{
		self::assertFalse( (new AnyFloatType( 22.5 ))->equals( new AnyFloatType( 22.0 ) ) );
	}

	public function testIfAnotherClassWithSameValueIsNotEqual()
	{
		self::assertFalse( (new AnyFloatType( 22.5 ))->equals( new AnotherFloatType( 22.5 ) ) );
	}

	public function testIfInvalidValueThrowsException(): void
	{
		$this->expectException( ValidationException::class );

		new class(12) extends AbstractFloatType {
			public static function isValid( float $value ): bool
			{
				return false;
			}
		};
	}

	public function testIfValidValueDoesNotThrowException(): void
	{
		$this->expectNotToPerformAssertions();

		new class(12) extends AbstractFloatType {
			public static function isValid( float $value ): bool
			{
				return true;
			}
		};
	}

	public function FloatComparisonDataProvider(): array
	{
		return [
			[ 0, 0, false, true, false ],
			[ PHP_INT_MIN, PHP_INT_MIN, false, true, false ],
			[ PHP_INT_MAX, PHP_INT_MAX, false, true, false ],
			[ 0, 1, true, false, false ],
			[ -1, 0, true, false, false ],
			[ PHP_INT_MIN, 0, true, false, false ],
			[ PHP_INT_MIN, PHP_INT_MAX, true, false, false ],
			[ 1, 0, false, false, true ],
			[ 0, -1, false, false, true ],
			[ 0, PHP_INT_MIN, false, false, true ],
			[ PHP_INT_MAX, PHP_INT_MIN, false, false, true ],
			[ 0.25, 0.26, true, false, false ],
			[ 0.25, 0.25, false, true, false ],
			[ 0.25, 0.24, false, false, true ],
			[ 0, 0.1, true, false, false ],
			[ 0, -0.1, false, false, true ],
		];
	}

	/**
	 * @dataProvider FloatComparisonDataProvider
	 *
	 * @param float $originalFloatValue
	 * @param float $anotherFloatValue
	 * @param bool  $isLess
	 * @param bool  $isEqual
	 * @param bool  $isGreater
	 */
	public function testComparingFloatValues( float $originalFloatValue, float $anotherFloatValue, bool $isLess, bool $isEqual, bool $isGreater ): void
	{
		$originalFloatType = new class($originalFloatValue) extends AbstractFloatType {
			public static function isValid( float $value ): bool
			{
				return true;
			}
		};
		$anotherFloatType  = new class($anotherFloatValue) extends AbstractFloatType {
			public static function isValid( float $value ): bool
			{
				return true;
			}
		};

		self::assertEquals( $isLess, $originalFloatType->isLessThan( $anotherFloatType ) );
		self::assertEquals( $isLess || $isEqual, $originalFloatType->isLessThanOrEqual( $anotherFloatType ) );
		self::assertEquals( $isEqual, $originalFloatType->isEqual( $anotherFloatType ) );
		self::assertEquals( $isGreater, $originalFloatType->isGreaterThan( $anotherFloatType ) );
		self::assertEquals( $isGreater || $isEqual, $originalFloatType->isGreaterThanOrEqual( $anotherFloatType ) );

		self::assertEquals( $isLess, $originalFloatType->isLessThan( $anotherFloatType->toFloat() ) );
		self::assertEquals( $isLess || $isEqual, $originalFloatType->isLessThanOrEqual( $anotherFloatType->toFloat() ) );
		self::assertEquals( $isEqual, $originalFloatType->isEqual( $anotherFloatType->toFloat() ) );
		self::assertEquals( $isGreater, $originalFloatType->isGreaterThan( $anotherFloatType->toFloat() ) );
		self::assertEquals( $isGreater || $isEqual, $originalFloatType->isGreaterThanOrEqual( $anotherFloatType->toFloat() ) );
	}

	public function testInitializeClassUsingTrait(): void
	{
		$floatType = new class(2.5) {
			use RepresentingFloatType;
		};

		self::assertEquals( 2.5, $floatType->toFloat() );
	}

	public function testIsZero(): void
	{
		self::assertTrue( (new AnyFloatType( 0 ))->isZero() );
		self::assertFalse( (new AnyFloatType( -0.1 ))->isZero() );
		self::assertFalse( (new AnyFloatType( 0.1 ))->isZero() );
	}

	public function testIsPositive(): void
	{
		self::assertFalse( (new AnyFloatType( 0 ))->isPositive() );
		self::assertFalse( (new AnyFloatType( -0.1 ))->isPositive() );
		self::assertTrue( (new AnyFloatType( 0.1 ))->isPositive() );
	}

	public function testIsNegative(): void
	{
		self::assertFalse( (new AnyFloatType( 0 ))->isNegative() );
		self::assertTrue( (new AnyFloatType( -0.1 ))->isNegative() );
		self::assertFalse( (new AnyFloatType( 0.1 ))->isNegative() );
	}

	public function testIsPositiveOrZero(): void
	{
		self::assertTrue( (new AnyFloatType( 0 ))->isPositiveOrZero() );
		self::assertFalse( (new AnyFloatType( -0.1 ))->isPositiveOrZero() );
		self::assertTrue( (new AnyFloatType( 0.1 ))->isPositiveOrZero() );
	}

	public function testIsNegativeOrZero(): void
	{
		self::assertTrue( (new AnyFloatType( 0 ))->isNegativeOrZero() );
		self::assertTrue( (new AnyFloatType( -0.1 ))->isNegativeOrZero() );
		self::assertFalse( (new AnyFloatType( 0.1 ))->isNegativeOrZero() );
	}

	public function FloatValueEqualityDataProvider(): array
	{
		return [
			[
				new AnyFloatType( 0 ),
				new AnotherFloatType( 0 ),
				true,
			],
			[
				new AnyFloatType( 0.1 ),
				new AnotherFloatType( 0.1 ),
				true,
			],
			[
				new AnyFloatType( -0.1 ),
				new AnotherFloatType( -0.1 ),
				true,
			],
			[
				new AnyFloatType( 0.1 ),
				new AnotherFloatType( 0.2 ),
				false,
			],
			[
				new AnyFloatType( -0.1 ),
				new AnotherFloatType( -0.2 ),
				false,
			],
			[
				new AnyFloatType( 0 ),
				new AnyFloatType( 0 ),
				true,
			],
			[
				new AnyFloatType( 0.1 ),
				new AnyFloatType( 0.1 ),
				true,
			],
			[
				new AnyFloatType( -0.1 ),
				new AnyFloatType( -0.1 ),
				true,
			],
			[
				new AnyFloatType( 0.1 ),
				new AnyFloatType( 0.2 ),
				false,
			],
			[
				new AnyFloatType( -0.1 ),
				new AnyFloatType( -0.2 ),
				false,
			],
		];
	}

	/**
	 * @dataProvider FloatValueEqualityDataProvider
	 *
	 * @param RepresentsFloatType $floatType
	 * @param RepresentsFloatType $anotherFloatType
	 * @param bool                $expectedEquals
	 *
	 * @return void
	 */
	public function testValueEquality( RepresentsFloatType $floatType, RepresentsFloatType $anotherFloatType, bool $expectedEquals ): void
	{
		self::assertEquals( $expectedEquals, $floatType->isEqual( $anotherFloatType ) );
		self::assertEquals( $expectedEquals, $floatType->isEqual( $anotherFloatType->toFloat() ) );
	}

	public function AdditionDataProvider(): array
	{
		return [
			[ new AnyFloatType( 0 ), new AnotherFloatType( 0 ), new AnyFloatType( 0 ) ],
			[ new AnyFloatType( 0 ), new AnyFloatType( 5 ), new AnyFloatType( 5 ) ],
			[ new AnotherFloatType( 5 ), new AnyFloatType( 0 ), new AnotherFloatType( 5 ) ],
			[ new AnyFloatType( 5 ), new AnyFloatType( 5 ), new AnyFloatType( 10 ) ],
			[ new AnyFloatType( -5 ), new AnyFloatType( 10 ), new AnyFloatType( 5 ) ],
		];
	}

	/**
	 * @dataProvider AdditionDataProvider
	 *
	 * @param RepresentsFloatType                   $originalFloatType
	 * @param RepresentsFloatType|RepresentsIntType $anotherType
	 * @param RepresentsFloatType                   $expectedFloatType
	 */
	public function testAddition( RepresentsFloatType $originalFloatType, RepresentsFloatType|RepresentsIntType $anotherType, RepresentsFloatType $expectedFloatType ): void
	{
		self::assertEquals( $expectedFloatType, $originalFloatType->add( $anotherType ) );
		self::assertEquals( $expectedFloatType, $originalFloatType->add( $anotherType->toFloat() ) );

		if ( $anotherType instanceof RepresentsIntType )
		{
			self::assertEquals( $expectedFloatType, $originalFloatType->add( $anotherType->toInt() ) );
		}
	}

	public function SubtractionDataProvider(): array
	{
		return [
			[ new AnyFloatType( 0 ), new AnotherFloatType( 0 ), new AnyFloatType( 0 ) ],
			[ new AnyFloatType( 0 ), new JustAnIntType( 0 ), new AnyFloatType( 0 ) ],
			[ new AnyFloatType( 0 ), new AnyFloatType( 5 ), new AnyFloatType( -5 ) ],
			[ new AnyFloatType( 0 ), new JustAnIntType( 5 ), new AnyFloatType( -5 ) ],
			[ new AnotherFloatType( 5 ), new AnyFloatType( 0 ), new AnotherFloatType( 5 ) ],
			[ new AnotherFloatType( 5 ), new JustAnIntType( 0 ), new AnotherFloatType( 5 ) ],
			[ new AnyFloatType( 5 ), new AnyFloatType( 5 ), new AnyFloatType( 0 ) ],
			[ new AnyFloatType( 5 ), new JustAnIntType( 5 ), new AnyFloatType( 0 ) ],
			[ new AnyFloatType( -5 ), new AnyFloatType( 10 ), new AnyFloatType( -15 ) ],
			[ new AnyFloatType( -5 ), new JustAnIntType( 10 ), new AnyFloatType( -15 ) ],
		];
	}

	/**
	 * @dataProvider SubtractionDataProvider
	 *
	 * @param RepresentsFloatType                   $originalFloatType
	 * @param RepresentsFloatType|RepresentsIntType $anotherType
	 * @param RepresentsFloatType                   $expectedFloatType
	 */
	public function testSubtraction( RepresentsFloatType $originalFloatType, RepresentsFloatType|RepresentsIntType $anotherType, RepresentsFloatType $expectedFloatType ): void
	{
		self::assertEquals( $expectedFloatType, $originalFloatType->subtract( $anotherType ) );
		self::assertEquals( $expectedFloatType, $originalFloatType->subtract( $anotherType->toFloat() ) );

		if ( $anotherType instanceof RepresentsIntType )
		{
			self::assertEquals( $expectedFloatType, $originalFloatType->subtract( $anotherType->toInt() ) );
		}
	}

	public function MultiplicationDataProvider(): array
	{
		return [
			[ new AnyFloatType( 0 ), new AnotherFloatType( 0 ), new AnyFloatType( 0 ) ],
			[ new AnyFloatType( 0 ), new JustAnIntType( 0 ), new AnyFloatType( 0 ) ],
			[ new AnyFloatType( 0 ), new AnyFloatType( 5 ), new AnyFloatType( 0 ) ],
			[ new AnyFloatType( 0 ), new JustAnIntType( 5 ), new AnyFloatType( 0 ) ],
			[ new AnotherFloatType( 5 ), new AnyFloatType( 0 ), new AnotherFloatType( 0 ) ],
			[ new AnotherFloatType( 5 ), new JustAnIntType( 0 ), new AnotherFloatType( 0 ) ],
			[ new AnyFloatType( 5 ), new AnyFloatType( 5 ), new AnyFloatType( 25 ) ],
			[ new AnyFloatType( 5 ), new JustAnIntType( 5 ), new AnyFloatType( 25 ) ],
			[ new AnyFloatType( -5 ), new AnyFloatType( 10 ), new AnyFloatType( -50 ) ],
			[ new AnyFloatType( -5 ), new JustAnIntType( 10 ), new AnyFloatType( -50 ) ],
		];
	}

	/**
	 * @dataProvider MultiplicationDataProvider
	 *
	 * @param RepresentsFloatType                   $originalFloatType
	 * @param RepresentsFloatType|RepresentsIntType $anotherType
	 * @param RepresentsFloatType                   $expectedFloatType
	 */
	public function testMultiplication( RepresentsFloatType $originalFloatType, RepresentsFloatType|RepresentsIntType $anotherType, RepresentsFloatType $expectedFloatType ): void
	{
		self::assertEquals( $expectedFloatType, $originalFloatType->multiply( $anotherType ) );
		self::assertEquals( $expectedFloatType, $originalFloatType->multiply( $anotherType->toFloat() ) );

		if ( $anotherType instanceof RepresentsIntType )
		{
			self::assertEquals( $expectedFloatType, $originalFloatType->multiply( $anotherType->toInt() ) );
		}
	}

	public function DivisionDataProvider(): array
	{
		return [
			[ new AnyFloatType( 0 ), new AnyFloatType( 5 ), new AnyFloatType( 0 ) ],
			[ new AnyFloatType( 0 ), new JustAnIntType( 5 ), new AnyFloatType( 0 ) ],
			[ new AnotherFloatType( 5 ), new AnyFloatType( 5 ), new AnotherFloatType( 1 ) ],
			[ new AnotherFloatType( 5 ), new JustAnIntType( 5 ), new AnotherFloatType( 1 ) ],
			[ new AnyFloatType( 25 ), new AnyFloatType( 5 ), new AnyFloatType( 5 ) ],
			[ new AnyFloatType( 25 ), new JustAnIntType( 5 ), new AnyFloatType( 5 ) ],
			[ new AnyFloatType( -50 ), new AnyFloatType( 10 ), new AnyFloatType( -5 ) ],
			[ new AnyFloatType( -50 ), new JustAnIntType( 10 ), new AnyFloatType( -5 ) ],
		];
	}

	/**
	 * @dataProvider DivisionDataProvider
	 *
	 * @param RepresentsFloatType                   $originalFloatType
	 * @param RepresentsFloatType|RepresentsIntType $anotherType
	 * @param RepresentsFloatType                   $expectedFloatType
	 */
	public function testDivision( RepresentsFloatType $originalFloatType, RepresentsFloatType|RepresentsIntType $anotherType, RepresentsFloatType $expectedFloatType ): void
	{
		self::assertEquals( $expectedFloatType, $originalFloatType->divide( $anotherType ) );
		self::assertEquals( $expectedFloatType, $originalFloatType->divide( $anotherType->toFloat() ) );

		if ( $anotherType instanceof RepresentsIntType )
		{
			self::assertEquals( $expectedFloatType, $originalFloatType->divide( $anotherType->toInt() ) );
		}
	}

	public function testTypeCasting(): void
	{
		self::assertEquals( '1.0', (new AnyFloatType( 1 ))->toString( 1 ) );
		self::assertEquals( '-1.0', (new AnyFloatType( -1 ))->toString( 1 ) );
		self::assertEquals( '0.0', (new AnyFloatType( 0 ))->toString( 1 ) );
		self::assertEquals( '1.26', (new AnyFloatType( 1.255 ))->toString( 2 ) );
		self::assertEquals( '-1.26', (new AnyFloatType( -1.255 ))->toString( 2 ) );
		self::assertEquals( '1.25', (new AnyFloatType( 1.254 ))->toString( 2 ) );
		self::assertEquals( '-1.25', (new AnyFloatType( -1.254 ))->toString( 2 ) );
	}

	public function testJsonSerialize(): void
	{
		$floatType = new AnyFloatType( 1.255 );

		self::assertSame( '1.255', json_encode( $floatType, JSON_THROW_ON_ERROR ) );
	}
}
