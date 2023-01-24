<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit;

use Fortuneglobe\Types\AbstractIntTypeArray;
use Fortuneglobe\Types\Exceptions\ValidationException;
use Fortuneglobe\Types\Interfaces\RepresentsIntType;
use Fortuneglobe\Types\IntTypeArray;
use Fortuneglobe\Types\Tests\Unit\Samples\AnotherIntType;
use Fortuneglobe\Types\Tests\Unit\Samples\JustAnIntType;
use Fortuneglobe\Types\Traits\RepresentingIntArrayType;
use PHPUnit\Framework\TestCase;

class IntTypeArrayTest extends TestCase
{
	public function testToArray(): void
	{
		$types     = [
			new JustAnIntType( 0 ),
			new JustAnIntType( 1 ),
			new JustAnIntType( 2 ),
		];
		$typeArray = new IntTypeArray( $types );

		self::assertEquals( [ 0, 1, 2 ], $typeArray->toArray() );
	}

	public function testIfSameObjectWithSameValuesEquals()
	{
		$object1 = (new IntTypeArray( [ new JustAnIntType( 0 ), new JustAnIntType( 1 ) ] ));
		$object2 = (new IntTypeArray( [ new JustAnIntType( 0 ), new JustAnIntType( 1 ) ] ));

		self::assertTrue( $object1->equals( $object2 ) );
	}

	public function testIfSameObjectWithDifferentValuesIsNotEqual()
	{
		$object1 = (new IntTypeArray( [ new JustAnIntType( 0 ), new JustAnIntType( 1 ) ] ));
		$object2 = (new IntTypeArray( [ new JustAnIntType( 0 ), new JustAnIntType( 2 ) ] ));

		self::assertFalse( $object1->equals( $object2 ) );
	}

	public function testIfSameObjectWithDifferentValueCountIsNotEqual()
	{
		$object1 = (new IntTypeArray( [ new JustAnIntType( 0 ), new JustAnIntType( 1 ) ] ));
		$object2 = (new IntTypeArray( [ new JustAnIntType( 0 ), new JustAnIntType( 1 ), new JustAnIntType( 1 ) ] ));

		self::assertFalse( $object1->equals( $object2 ) );
	}

	public function testIfDifferentObjectWithSameValuesIsNotEqual()
	{
		$object1 = (new IntTypeArray( [ new AnotherIntType( 0 ), new JustAnIntType( 1 ) ] ));
		$object2 = (new IntTypeArray( [ new JustAnIntType( 0 ), new JustAnIntType( 1 ) ] ));

		self::assertFalse( $object1->equals( $object2 ) );
	}

	public function testIfInvalidValueThrowsException(): void
	{
		$this->expectException( ValidationException::class );

		/** @noinspection PhpParamsInspection */
		new IntTypeArray( [ 0, 1 ] );
	}

	public function testIfValidValueDoesNotThrowException(): void
	{
		$this->expectNotToPerformAssertions();

		new IntTypeArray( [ new JustAnIntType( 0 ), new AnotherIntType( 1 ) ] );
	}

	public function EqualsValueDataProvider(): array
	{
		return [
			[
				new IntTypeArray(
					[
						new JustAnIntType( 0 ),
						new JustAnIntType( 1 ),
						new JustAnIntType( 2 ),
					]
				),
				new IntTypeArray(
					[
						new JustAnIntType( 0 ),
						new JustAnIntType( 1 ),
						new JustAnIntType( 2 ),
					]
				),
				true,
			],
			[
				new IntTypeArray(
					[
						new JustAnIntType( 0 ),
						new AnotherIntType( 1 ),
						new JustAnIntType( 2 ),
					]
				),
				new IntTypeArray(
					[
						new JustAnIntType( 0 ),
						new AnotherIntType( 1 ),
						new JustAnIntType( 2 ),
					]
				),
				true,
			],
			[
				new IntTypeArray(
					[
						new JustAnIntType( 0 ),
						new AnotherIntType( 1 ),
						new JustAnIntType( 2 ),
					]
				),
				new IntTypeArray(
					[
						new AnotherIntType( 1 ),
						new JustAnIntType( 0 ),
						new JustAnIntType( 2 ),
					]
				),
				true,
			],
			[
				new IntTypeArray(
					[
						new JustAnIntType( 0 ),
						new AnotherIntType( 1 ),
						new JustAnIntType( 2 ),
					]
				),
				new IntTypeArray(
					[
						new JustAnIntType( 0 ),
						new AnotherIntType( 1 ),
						new JustAnIntType( 0 ),
					]
				),
				false,
			],
			[

				new IntTypeArray(
					[
						new JustAnIntType( 0 ),
						new AnotherIntType( 1 ),
						new JustAnIntType( 2 ),
					]
				),
				new IntTypeArray(
					[
						new JustAnIntType( 0 ),
						new AnotherIntType( 1 ),
						new AnotherIntType( 2 ),
					]
				),
				false,
			],
		];
	}

	/**
	 * @dataProvider EqualsValueDataProvider
	 *
	 * @param IntTypeArray $intTypes
	 * @param IntTypeArray $otherIntTypes
	 * @param bool         $expectedResult
	 */
	public function testIfEqualsValueComparesOnlyValues( IntTypeArray $intTypes, IntTypeArray $otherIntTypes, bool $expectedResult ): void
	{
		self::assertSame( $expectedResult, $intTypes->equalsValues( $otherIntTypes ) );
	}

	public function ToJsonDataProvider(): array
	{
		return [
			[
				new IntTypeArray( [ new JustAnIntType( 0 ), new AnotherIntType( 1 ), new JustAnIntType( 2 ), ] ),
				json_encode( [ 0, 1, 2, ], JSON_THROW_ON_ERROR ),
			],
		];
	}

	public function testToJson(): void
	{
		$intTypes     = new IntTypeArray( [ new JustAnIntType( 0 ), new AnotherIntType( 1 ), new JustAnIntType( 2 ), ] );
		$expectedJson = json_encode( [ 0, 1, 2, ], JSON_THROW_ON_ERROR );

		self::assertSame( $expectedJson, $intTypes->toJson() );
	}

	public function testCountingElements(): void
	{
		self::assertCount( 0, new IntTypeArray( [] ) );
		self::assertCount( 1, new IntTypeArray( [ new JustAnIntType( 0 ) ] ) );
		self::assertCount( 1, new IntTypeArray( [ 0 => new JustAnIntType( 0 ) ] ) );
		self::assertCount( 2, new IntTypeArray( [ new JustAnIntType( 0 ), new AnotherIntType( 0 ) ] ) );
		self::assertCount( 3, new IntTypeArray( [ new JustAnIntType( 0 ), new AnotherIntType( 1 ), new AnotherIntType( 2 ) ] ) );
	}

	public function testOffsetMethods(): void
	{
		$intTypes    = new IntTypeArray( [] );
		$intTypes[0] = new JustAnIntType( 1 );

		self::assertEquals( new JustAnIntType( 1 ), $intTypes[0] );

		unset( $intTypes[1] );

		self::assertNotContains( 0, $intTypes );
	}

	public function testIterationMethod(): void
	{
		$intTypes = new IntTypeArray(
			[
				'first'  => new JustAnIntType( 0 ),
				'second' => new AnotherIntType( 1 ),
				'last'   => new JustAnIntType( 2 ),
			]
		);

		self::assertEquals( new JustAnIntType( 0 ), $intTypes->current() );
		self::assertEquals( 'first', $intTypes->key() );
		self::assertEquals( true, $intTypes->valid() );

		$intTypes->next();

		self::assertEquals( new AnotherIntType( 1 ), $intTypes->current() );
		self::assertEquals( 'second', $intTypes->key() );
		self::assertEquals( true, $intTypes->valid() );

		$intTypes->next();

		self::assertEquals( new JustAnIntType( 2 ), $intTypes->current() );
		self::assertEquals( 'last', $intTypes->key() );
		self::assertEquals( true, $intTypes->valid() );

		$intTypes->rewind();

		self::assertEquals( new JustAnIntType( 0 ), $intTypes->current() );
		self::assertEquals( 'first', $intTypes->key() );
		self::assertEquals( true, $intTypes->valid() );
	}

	public function testIfAnotherIntTypeArrayAllowsOnlyJustAnIntType(): void
	{
		$this->expectException( ValidationException::class );

		new class([ new JustAnIntType( 0 ), new AnotherIntType( 1 ) ]) extends AbstractIntTypeArray {
			protected static function isValid( RepresentsIntType $intType ): bool
			{
				return $intType instanceof JustAnIntType;
			}
		};
	}

	public function testIfAllowedTypeDoesNotThrowException(): void
	{
		$this->expectNotToPerformAssertions();

		new class([ new JustAnIntType( 0 ), new JustAnIntType( 1 ) ]) extends AbstractIntTypeArray {
			protected static function isValid( RepresentsIntType $intType ): bool
			{
				return $intType instanceof JustAnIntType;
			}
		};
	}

	public function testInitializeClassUsingTrait(): void
	{
		$this->expectNotToPerformAssertions();

		new class([ new JustAnIntType( 0 ), new JustAnIntType( 1 ) ]) {
			use RepresentingIntArrayType;
		};
	}
}
