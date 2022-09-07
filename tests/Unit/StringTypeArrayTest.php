<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit;

use Fortuneglobe\Types\AbstractStringTypeArray;
use Fortuneglobe\Types\Exceptions\ValidationException;
use Fortuneglobe\Types\Interfaces\RepresentsStringType;
use Fortuneglobe\Types\StringTypeArray;
use Fortuneglobe\Types\Tests\Unit\Samples\AnotherStringType;
use Fortuneglobe\Types\Tests\Unit\Samples\AnyStringType;
use Fortuneglobe\Types\Traits\RepresentingStringArrayType;
use PHPUnit\Framework\TestCase;

class StringTypeArrayTest extends TestCase
{
	public function testToArray(): void
	{
		$types     = [
			new AnyStringType( 'a' ),
			new AnyStringType( 'b' ),
			new AnyStringType( 'c' ),
		];
		$typeArray = new StringTypeArray( $types );

		self::assertEquals( [ 'a', 'b', 'c' ], $typeArray->toArray() );
	}

	public function testIfSameObjectWithSameValuesEquals()
	{
		$object1 = (new StringTypeArray( [ new AnyStringType( 'a' ), new AnyStringType( 'b' ) ] ));
		$object2 = (new StringTypeArray( [ new AnyStringType( 'a' ), new AnyStringType( 'b' ) ] ));

		self::assertTrue( $object1->equals( $object2 ) );
	}

	public function testIfSameObjectWithDifferentValuesIsNotEqual()
	{
		$object1 = (new StringTypeArray( [ new AnyStringType( 'a' ), new AnyStringType( 'b' ) ] ));
		$object2 = (new StringTypeArray( [ new AnyStringType( 'a' ), new AnyStringType( 'c' ) ] ));

		self::assertFalse( $object1->equals( $object2 ) );
	}

	public function testIfDifferentObjectWithSameValuesIsNotEqual()
	{
		$object1 = (new StringTypeArray( [ new AnotherStringType( 'a' ), new AnyStringType( 'b' ) ] ));
		$object2 = (new StringTypeArray( [ new AnyStringType( 'a' ), new AnyStringType( 'b' ) ] ));

		self::assertFalse( $object1->equals( $object2 ) );
	}

	public function testIfInvalidValueThrowsException(): void
	{
		$this->expectException( ValidationException::class );

		/** @noinspection PhpParamsInspection */
		new StringTypeArray( [ 'a', 'b' ] );
	}

	public function testIfValidValueDoesNotThrowException(): void
	{
		$this->expectNotToPerformAssertions();

		new StringTypeArray( [ new AnyStringType( 'a' ), new AnotherStringType( 'b' ) ] );
	}

	public function EqualsValueDataProvider(): array
	{
		return [
			[
				new StringTypeArray(
					[
						new AnyStringType( 'a' ),
						new AnyStringType( 'b' ),
						new AnyStringType( 'c' ),
					]
				),
				new StringTypeArray(
					[
						new AnyStringType( 'a' ),
						new AnyStringType( 'b' ),
						new AnyStringType( 'c' ),
					]
				),
				true,
			],
			[
				new StringTypeArray(
					[
						new AnyStringType( 'a' ),
						new AnotherStringType( 'b' ),
						new AnyStringType( 'c' ),
					]
				),
				new StringTypeArray(
					[
						new AnyStringType( 'a' ),
						new AnotherStringType( 'b' ),
						new AnyStringType( 'c' ),
					]
				),
				true,
			],
			[
				new StringTypeArray(
					[
						new AnyStringType( 'a' ),
						new AnotherStringType( 'b' ),
						new AnyStringType( 'c' ),
					]
				),
				new StringTypeArray(
					[
						new AnotherStringType( 'b' ),
						new AnyStringType( 'a' ),
						new AnyStringType( 'c' ),
					]
				),
				true,
			],
			[
				new StringTypeArray(
					[
						new AnyStringType( 'a' ),
						new AnotherStringType( 'b' ),
						new AnyStringType( 'c' ),
					]
				),
				new StringTypeArray(
					[
						new AnyStringType( 'a' ),
						new AnotherStringType( 'b' ),
						new AnyStringType( 'a' ),
					]
				),
				false,
			],
			[

				new StringTypeArray(
					[
						new AnyStringType( 'a' ),
						new AnotherStringType( 'b' ),
						new AnyStringType( 'c' ),
					]
				),
				new StringTypeArray(
					[
						new AnyStringType( 'a' ),
						new AnotherStringType( 'b' ),
						new AnotherStringType( 'c' ),
					]
				),
				false,
			],
		];
	}

	/**
	 * @dataProvider EqualsValueDataProvider
	 *
	 * @param StringTypeArray $stringTypes
	 * @param StringTypeArray $anotherStringTypes
	 * @param bool            $expectedResult
	 */
	public function testIfEqualsValueComparesOnlyValues( StringTypeArray $stringTypes, StringTypeArray $anotherStringTypes, bool $expectedResult ): void
	{
		self::assertSame( $expectedResult, $stringTypes->equalsValues( $anotherStringTypes ) );
	}

	public function ToJsonDataProvider(): array
	{
		return [
			[
				new StringTypeArray( [ new AnyStringType( 'a' ), new AnotherStringType( 'b' ), new AnyStringType( 'c' ), ] ),
				json_encode( [ 'a', 'b', 'c', ], JSON_THROW_ON_ERROR ),
			],
		];
	}

	public function testToJson(): void
	{
		$stringTypes  = new StringTypeArray( [ new AnyStringType( 'a' ), new AnotherStringType( 'b' ), new AnyStringType( 'c' ), ] );
		$expectedJson = json_encode( [ 'a', 'b', 'c', ], JSON_THROW_ON_ERROR );

		self::assertSame( $expectedJson, $stringTypes->toJson() );
	}

	public function testCountingElements(): void
	{
		self::assertCount( 0, new StringTypeArray( [] ) );
		self::assertCount( 1, new StringTypeArray( [ new AnyStringType( 'a' ) ] ) );
		self::assertCount( 1, new StringTypeArray( [ 'a' => new AnyStringType( 'a' ) ] ) );
		self::assertCount( 2, new StringTypeArray( [ new AnyStringType( 'a' ), new AnotherStringType( 'a' ) ] ) );
		self::assertCount( 3, new StringTypeArray( [ new AnyStringType( 'a' ), new AnotherStringType( 'b' ), new AnotherStringType( 'c' ) ] ) );
	}

	public function testOffsetMethods(): void
	{
		$stringTypes      = new StringTypeArray( [] );
		$stringTypes['a'] = new AnyStringType( 'b' );

		self::assertEquals( new AnyStringType( 'b' ), $stringTypes['a'] );

		unset( $stringTypes['b'] );

		self::assertNotContains( 'a', $stringTypes );
	}

	public function testIterationMethod(): void
	{
		$stringTypes = new StringTypeArray(
			[
				'first'  => new AnyStringType( 'a' ),
				'second' => new AnotherStringType( 'b' ),
				'last'   => new AnyStringType( 'c' ),
			]
		);

		self::assertEquals( new AnyStringType( 'a' ), $stringTypes->current() );
		self::assertEquals( 'first', $stringTypes->key() );
		self::assertEquals( true, $stringTypes->valid() );

		$stringTypes->next();

		self::assertEquals( new AnotherStringType( 'b' ), $stringTypes->current() );
		self::assertEquals( 'second', $stringTypes->key() );
		self::assertEquals( true, $stringTypes->valid() );

		$stringTypes->next();

		self::assertEquals( new AnyStringType( 'c' ), $stringTypes->current() );
		self::assertEquals( 'last', $stringTypes->key() );
		self::assertEquals( true, $stringTypes->valid() );

		$stringTypes->rewind();

		self::assertEquals( new AnyStringType( 'a' ), $stringTypes->current() );
		self::assertEquals( 'first', $stringTypes->key() );
		self::assertEquals( true, $stringTypes->valid() );
	}

	public function testIfAnotherStringTypeArrayAllowsOnlyAnyStringType(): void
	{
		$this->expectException( ValidationException::class );

		new class([ new AnyStringType( 'a' ), new AnotherStringType( 'b' ) ]) extends AbstractStringTypeArray
		{
			protected static function isValid( RepresentsStringType $stringType ): bool
			{
				return $stringType instanceof AnyStringType;
			}
		};
	}

	public function testIfAllowedTypeDoesNotThrowException(): void
	{
		$this->expectNotToPerformAssertions();

		new class([ new AnyStringType( 'a' ), new AnyStringType( 'b' ) ]) extends AbstractStringTypeArray
		{
			protected static function isValid( RepresentsStringType $stringType ): bool
			{
				return $stringType instanceof AnyStringType;
			}
		};
	}

	public function testInitializeClassUsingTrait(): void
	{
		$this->expectNotToPerformAssertions();

		new class([ new AnyStringType( 'a' ), new AnyStringType( 'b' ) ])
		{
			use RepresentingStringArrayType;
		};
	}
}
