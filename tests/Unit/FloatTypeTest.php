<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit;

use Fortuneglobe\Types\AbstractFloatType;
use Fortuneglobe\Types\Exceptions\ValidationException;
use Fortuneglobe\Types\Interfaces\RepresentsFloatType;
use Fortuneglobe\Types\Tests\Unit\Samples\AnotherFloatType;
use Fortuneglobe\Types\Tests\Unit\Samples\AnyFloatType;
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

		new class(12) extends AbstractFloatType
		{
			public static function isValid( float $value ): bool
			{
				return false;
			}
		};
	}

	public function testIfValidValueDoesNotThrowException(): void
	{
		$this->expectNotToPerformAssertions();

		new class(12) extends AbstractFloatType
		{
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
	 * @param int  $originalFloatValue
	 * @param int  $anotherFloatValue
	 * @param bool $isLess
	 * @param bool $isEqual
	 * @param bool $isGreater
	 */
	public function testComparingIntValues( float $originalFloatValue, float $anotherFloatValue, bool $isLess, bool $isEqual, bool $isGreater ): void
	{
		$originalIntType = new class($originalFloatValue) extends AbstractFloatType
		{
			public static function isValid( float $value ): bool
			{
				return true;
			}
		};
		$anotherIntType  = new class($anotherFloatValue) extends AbstractFloatType
		{
			public static function isValid( float $value ): bool
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

	public function testInitializeClassUsingTrait(): void
	{
		$floatType = new class(2.5)
		{
			use RepresentingFloatType;
		};

		self::assertEquals( 2.5, $floatType->toFloat() );
	}
}
