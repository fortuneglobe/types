<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit;

use Fortuneglobe\Types\AbstractIntType;
use Fortuneglobe\Types\Exceptions\ValidationException;
use Fortuneglobe\Types\Interfaces\RepresentsIntType;
use Fortuneglobe\Types\Tests\Unit\Samples\AnotherIntType;
use Fortuneglobe\Types\Tests\Unit\Samples\JustAnIntType;
use Fortuneglobe\Types\Tests\Unit\Samples\NoZeroIntType;
use PHPUnit\Framework\TestCase;

class IntTypeTest extends TestCase
{
	public function testIfInvalidValueThrowsException(): void
	{
		$this->expectException( ValidationException::class );

		new class(12) extends AbstractIntType
		{
			public static function isValid( int $value ): bool
			{
				return false;
			}
		};
	}

	public function testIfValidValueDoesNotThrowException(): void
	{
		$this->expectNotToPerformAssertions();

		new class(12) extends AbstractIntType
		{
			public static function isValid( int $value ): bool
			{
				return true;
			}
		};
	}

	public function IntTypeProvider(): array
	{
		return [
			[
				new JustAnIntType( 12 ),
				new JustAnIntType( 12 ),
				true,
			],
			[
				new JustAnIntType( 0 ),
				new JustAnIntType( 0 ),
				true,
			],
			[
				new JustAnIntType( -100 ),
				new JustAnIntType( -100 ),
				true,
			],
			[
				new JustAnIntType( -12 ),
				new JustAnIntType( 12 ),
				false,
			],
			[
				new JustAnIntType( PHP_INT_MIN ),
				new JustAnIntType( PHP_INT_MAX ),
				false,
			],
			[
				new JustAnIntType( 2 ),
				new AnotherIntType( 2 ),
				false,
			],
		];
	}

	/**
	 * @dataProvider IntTypeProvider
	 *
	 * @param AbstractIntType $intType
	 * @param AbstractIntType $anotherIntType
	 * @param bool            $equals
	 */
	public function testIntTypeEquality( AbstractIntType $intType, AbstractIntType $anotherIntType, bool $equals ): void
	{
		self::assertEquals( $equals, $intType->equals( $anotherIntType ) );
	}

	public function testIfIntTypeCanBeInstantiatedByAnotherIntType(): void
	{
		$intType = JustAnIntType::fromIntType( new AnotherIntType( 12 ) );

		self::assertEquals( new JustAnIntType( 12 ), $intType );
	}

	public function IntComparisonDataProvider(): array
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
		];
	}

	/**
	 * @dataProvider IntComparisonDataProvider
	 *
	 * @param int  $originalIntValue
	 * @param int  $anotherIntValue
	 * @param bool $isLess
	 * @param bool $isEqual
	 * @param bool $isGreater
	 */
	public function testComparingIntValues( int $originalIntValue, int $anotherIntValue, bool $isLess, bool $isEqual, bool $isGreater ): void
	{
		$originalIntType = new class($originalIntValue) extends AbstractIntType
		{
			public static function isValid( int $value ): bool
			{
				return true;
			}
		};
		$anotherIntType  = new class($anotherIntValue) extends AbstractIntType
		{
			public static function isValid( int $value ): bool
			{
				return true;
			}
		};

		self::assertEquals( $isLess, $originalIntType->isLessThan( $anotherIntType ) );
		self::assertEquals( $isLess || $isEqual, $originalIntType->isLessThanOrEqual( $anotherIntType ) );
		self::assertEquals( $isEqual, $originalIntType->isEqual( $anotherIntType ) );
		self::assertEquals( $isGreater, $originalIntType->isGreaterThan( $anotherIntType ) );
		self::assertEquals( $isGreater || $isEqual, $originalIntType->isGreaterThanOrEqual( $anotherIntType ) );
	}

	public function AdditionDataProvider(): array
	{
		return [
			[ new JustAnIntType( 0 ), new AnotherIntType( 0 ), new JustAnIntType( 0 ) ],
			[ new JustAnIntType( 0 ), new JustAnIntType( 5 ), new JustAnIntType( 5 ) ],
			[ new AnotherIntType( 5 ), new JustAnIntType( 0 ), new AnotherIntType( 5 ) ],
			[ new JustAnIntType( 5 ), new JustAnIntType( 5 ), new JustAnIntType( 10 ) ],
			[ new JustAnIntType( -5 ), new JustAnIntType( 10 ), new JustAnIntType( 5 ) ],
			[ new JustAnIntType( PHP_INT_MIN ), new JustAnIntType( PHP_INT_MAX ), new JustAnIntType( PHP_INT_MIN + PHP_INT_MAX ) ],
		];
	}

	/**
	 * @dataProvider AdditionDataProvider
	 *
	 * @param RepresentsIntType $originalIntType
	 * @param RepresentsIntType $anotherIntType
	 * @param RepresentsIntType $expectedIntType
	 */
	public function testAddition( RepresentsIntType $originalIntType, RepresentsIntType $anotherIntType, RepresentsIntType $expectedIntType ): void
	{
		self::assertEquals( $expectedIntType, $originalIntType->add( $anotherIntType ) );
	}

	public function SubtractionDataProvider(): array
	{
		return [
			[ new JustAnIntType( 0 ), new AnotherIntType( 0 ), new JustAnIntType( 0 ) ],
			[ new JustAnIntType( 0 ), new JustAnIntType( 5 ), new JustAnIntType( -5 ) ],
			[ new AnotherIntType( 5 ), new JustAnIntType( 0 ), new AnotherIntType( 5 ) ],
			[ new JustAnIntType( 5 ), new JustAnIntType( 5 ), new JustAnIntType( 0 ) ],
			[ new JustAnIntType( -5 ), new JustAnIntType( 10 ), new JustAnIntType( -15 ) ],
		];
	}

	/**
	 * @dataProvider SubtractionDataProvider
	 *
	 * @param RepresentsIntType $originalIntType
	 * @param RepresentsIntType $anotherIntType
	 * @param RepresentsIntType $expectedIntType
	 */
	public function testSubtraction( RepresentsIntType $originalIntType, RepresentsIntType $anotherIntType, RepresentsIntType $expectedIntType ): void
	{
		self::assertEquals( $expectedIntType, $originalIntType->subtract( $anotherIntType ) );
	}

	public function IncrementDataProvider(): array
	{
		return [
			[ new JustAnIntType( 0 ), 0, new JustAnIntType( 0 ) ],
			[ new JustAnIntType( 0 ), 5, new JustAnIntType( 5 ) ],
			[ new AnotherIntType( 5 ), 0, new AnotherIntType( 5 ) ],
			[ new JustAnIntType( 5 ), 5, new JustAnIntType( 10 ) ],
			[ new JustAnIntType( -5 ), 10, new JustAnIntType( 5 ) ],
			[ new JustAnIntType( -5 ), 1, new JustAnIntType( -4 ) ],
			[ new JustAnIntType( 0 ), 1, new JustAnIntType( 1 ) ],
		];
	}

	/**
	 * @dataProvider IncrementDataProvider
	 *
	 * @param RepresentsIntType $originalIntType
	 * @param int               $value
	 * @param RepresentsIntType $expectedIntType
	 */
	public function testIncrement( RepresentsIntType $originalIntType, int $value, RepresentsIntType $expectedIntType ): void
	{
		self::assertEquals( $expectedIntType, $originalIntType->increment( $value ) );
	}

	public function DecrementDataProvider(): array
	{
		return [
			[ new JustAnIntType( 0 ), 0, new JustAnIntType( 0 ) ],
			[ new JustAnIntType( 0 ), 5, new JustAnIntType( -5 ) ],
			[ new AnotherIntType( 5 ), 0, new AnotherIntType( 5 ) ],
			[ new JustAnIntType( 5 ), 5, new JustAnIntType( 0 ) ],
			[ new JustAnIntType( -5 ), 10, new JustAnIntType( -15 ) ],
			[ new JustAnIntType( -5 ), 1, new JustAnIntType( -6 ) ],
			[ new JustAnIntType( 0 ), 1, new JustAnIntType( -1 ) ],
			[ new JustAnIntType( 1 ), 1, new JustAnIntType( 0 ) ],
		];
	}

	/**
	 * @dataProvider DecrementDataProvider
	 *
	 * @param RepresentsIntType $originalIntType
	 * @param int               $value
	 * @param RepresentsIntType $expectedIntType
	 */
	public function testDecrement( RepresentsIntType $originalIntType, int $value, RepresentsIntType $expectedIntType ): void
	{
		self::assertEquals( $expectedIntType, $originalIntType->decrement( $value ) );
	}

	public function ToStringDataProvider(): array
	{
		return [
			[ new JustAnIntType( 1 ), '1' ],
			[ new JustAnIntType( -1 ), '-1' ],
			[ new JustAnIntType( 0 ), '0' ],
		];
	}

	/**
	 * @dataProvider ToStringDataProvider
	 *
	 * @param RepresentsIntType $intType
	 * @param string            $expectedString
	 */
	public function testToString( RepresentsIntType $intType, string $expectedString ): void
	{
		self::assertSame( $expectedString, (string)$intType );
	}

	public function ToIntDataProvider(): array
	{
		return [
			[ new JustAnIntType( 1 ), 1 ],
			[ new JustAnIntType( -1 ), -1 ],
			[ new JustAnIntType( 0 ), 0 ],
		];
	}

	/**
	 * @dataProvider ToIntDataProvider
	 *
	 * @param RepresentsIntType $intType
	 * @param int               $expectedInt
	 */
	public function testToInt( RepresentsIntType $intType, int $expectedInt ): void
	{
		self::assertSame( $expectedInt, $intType->toInt() );
	}

	public function testInvalidValueThrowsException(): void
	{
		$this->expectException( ValidationException::class );
		$this->expectExceptionMessage( 'Invalid NoZeroIntType: 0' );

		new NoZeroIntType( 0 );
	}

	public function testFromIntType(): void
	{
		self::assertEquals( new JustAnIntType( 15 ), JustAnIntType::fromIntType( new AnotherIntType( 15 ) ) );
	}
}


