<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit;

use Fortuneglobe\Types\AbstractStringType;
use Fortuneglobe\Types\Exceptions\ValidationException;
use Fortuneglobe\Types\Interfaces\RepresentsStringType;
use Fortuneglobe\Types\Tests\Unit\Samples\AnotherStringType;
use Fortuneglobe\Types\Tests\Unit\Samples\AnyStringType;
use Fortuneglobe\Types\Tests\Unit\Samples\NoQuestionMarkStringType;
use Fortuneglobe\Types\Traits\RepresentingStringType;
use PHPUnit\Framework\TestCase;

class StringTypeTest extends TestCase
{
	public function ToStringDataProvider(): array
	{
		return [
			[ new AnyStringType( 'test' ), 'test' ],
			[ new AnyStringType( '??!!' ), '??!!' ],
			[ new AnyStringType( 'Just a small phrase' ), 'Just a small phrase' ],
			[ new AnyStringType( '' ), '' ],
			[ new AnyStringType( ' ' ), ' ' ],
		];
	}

	/**
	 * @dataProvider ToStringDataProvider
	 *
	 * @param RepresentsStringType $stringType
	 * @param string               $expectedString
	 */
	public function testToString( RepresentsStringType $stringType, string $expectedString ): void
	{
		self::assertSame( $expectedString, $stringType->toString() );
		self::assertSame( $expectedString, (string)$stringType );
	}

	public function testInvalidValueThrowsException(): void
	{
		$this->expectException( ValidationException::class );
		$this->expectExceptionMessage( 'Invalid NoQuestionMarkStringType: ??' );

		new NoQuestionMarkStringType( '??' );
	}

	public function testFromStringType(): void
	{
		self::assertEquals( new AnyStringType( 'Test' ), AnyStringType::fromStringType( new AnotherStringType( 'Test' ) ) );
	}

	public function testIfSameClassWithSameValueEquals()
	{
		self::assertTrue( (new AnyStringType( 'Test' ))->equals( new AnyStringType( 'Test' ) ) );
	}

	public function testIfSameClassWithAnotherValueIsNotEqual()
	{
		self::assertFalse( (new AnyStringType( 'Test' ))->equals( new AnyStringType( 'test' ) ) );
	}

	public function testIfAnotherClassWithSameValueIsNotEqual()
	{
		self::assertFalse( (new AnyStringType( 'Test' ))->equals( new AnotherStringType( 'Test' ) ) );
	}

	public function testIfInvalidValueThrowsException(): void
	{
		$this->expectException( ValidationException::class );

		new class('Test') extends AbstractStringType
		{
			public static function isValid( string $value ): bool
			{
				return false;
			}
		};
	}

	public function testIfValidValueDoesNotThrowException(): void
	{
		$this->expectNotToPerformAssertions();

		new class('Test') extends AbstractStringType
		{
			public static function isValid( string $value ): bool
			{
				return true;
			}
		};
	}

	public function EqualsValueDataProvider(): array
	{
		return [
			[
				new AnyStringType( 'Test 1' ),
				new AnotherStringType( 'Test 1' ),
				true,
			],
			[
				new AnyStringType( 'Test 1' ),
				new AnyStringType( 'Test 1' ),
				true,
			],
			[
				new AnyStringType( 'Test 1' ),
				new AnotherStringType( 'Test 2' ),
				false,
			],
			[
				new AnyStringType( 'Test 1' ),
				new AnyStringType( 'Test 2' ),
				false,
			],
		];
	}

	/**
	 * @dataProvider EqualsValueDataProvider
	 *
	 * @param RepresentsStringType $stringType
	 * @param RepresentsStringType $anotherStringType
	 * @param bool                 $expectedResult
	 */
	public function testIfEqualsValueComparesOnlyValues( RepresentsStringType $stringType, RepresentsStringType $anotherStringType, bool $expectedResult ): void
	{
		self::assertSame( $expectedResult, $stringType->equalsValue( $anotherStringType ) );
	}

	public function testIsEmpty(): void
	{
		self::assertTrue( (new AnyStringType( '' ))->isEmpty() );
		self::assertFalse( (new AnyStringType( '0' ))->isEmpty() );
		self::assertFalse( (new AnyStringType( ' ' ))->isEmpty() );
	}

	public function testInitializeClassUsingTrait(): void
	{
		$stringType = new class('a')
		{
			use RepresentingStringType;
		};

		self::assertEquals( 'a', $stringType->toString() );
	}
}
