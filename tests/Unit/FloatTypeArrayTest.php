<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit;

use Fortuneglobe\Types\AbstractFloatTypeArray;
use Fortuneglobe\Types\Exceptions\ValidationException;
use Fortuneglobe\Types\FloatTypeArray;
use Fortuneglobe\Types\Interfaces\RepresentsFloatType;
use Fortuneglobe\Types\Tests\Unit\Samples\AnotherFloatType;
use Fortuneglobe\Types\Tests\Unit\Samples\AnotherFloatTypeArray;
use Fortuneglobe\Types\Tests\Unit\Samples\AnyFloatType;
use Fortuneglobe\Types\Traits\RepresentingFloatArrayType;
use PHPUnit\Framework\TestCase;

class FloatTypeArrayTest extends TestCase
{
	public function testToArray(): void
	{
		$types     = [
			new AnyFloatType( 0.5 ),
			new AnyFloatType( 1.5 ),
			new AnyFloatType( 2.5 ),
		];
		$typeArray = new FloatTypeArray( $types );

		self::assertEquals( [ 0.5, 1.5, 2.5 ], $typeArray->toArray() );
	}

	public function testIfSameObjectWithSameValuesEquals()
	{
		$object1 = (new FloatTypeArray( [ new AnyFloatType( 0.5 ), new AnyFloatType( 1.5 ) ] ));
		$object2 = (new FloatTypeArray( [ new AnyFloatType( 0.5 ), new AnyFloatType( 1.5 ) ] ));

		self::assertTrue( $object1->equals( $object2 ) );
	}

	public function testIfSameObjectWithDifferentValuesIsNotEqual()
	{
		$object1 = (new FloatTypeArray( [ new AnyFloatType( 0.5 ), new AnyFloatType( 1.5 ) ] ));
		$object2 = (new FloatTypeArray( [ new AnyFloatType( 0.5 ), new AnyFloatType( 2.5 ) ] ));

		self::assertFalse( $object1->equals( $object2 ) );
	}

	public function testIfSameObjectWithDifferentValueCountIsNotEqual()
	{
		$object1 = (new FloatTypeArray( [ new AnyFloatType( 0.5 ), new AnyFloatType( 1.5 ) ] ));
		$object2 = (new FloatTypeArray( [ new AnyFloatType( 0.5 ), new AnyFloatType( 1.5 ), new AnyFloatType( 1.5 ) ] ));

		self::assertFalse( $object1->equals( $object2 ) );
	}

	public function testIfSameObjectWithDifferentTypesAndSameValuesIsNotEqual()
	{
		$object1 = (new FloatTypeArray( [ new AnotherFloatType( 0.5 ), new AnyFloatType( 1.5 ) ] ));
		$object2 = (new FloatTypeArray( [ new AnyFloatType( 0.5 ), new AnyFloatType( 1.5 ) ] ));

		self::assertFalse( $object1->equals( $object2 ) );
	}

	public function testIfDifferentObjectWithSameTypesAndValuesIsNotEqual()
	{
		$object1 = (new FloatTypeArray( [ new AnotherFloatType( 0.5 ), new AnyFloatType( 1.5 ) ] ));
		$object2 = (new AnotherFloatTypeArray( [ new AnotherFloatType( 0.5 ), new AnyFloatType( 1.5 ) ] ));

		self::assertFalse( $object1->equals( $object2 ) );
	}

	public function testIfInvalidValueThrowsException(): void
	{
		$this->expectException( ValidationException::class );

		/** @noinspection PhpParamsInspection */
		new FloatTypeArray( [ 0.5, 1.5 ] );
	}

	public function testIfValidValueDoesNotThrowException(): void
	{
		$this->expectNotToPerformAssertions();

		new FloatTypeArray( [ new AnyFloatType( 0.5 ), new AnotherFloatType( 1.5 ) ] );
	}

	public function EqualsValueDataProvider(): array
	{
		return [
			[
				new FloatTypeArray(
					[
						new AnyFloatType( 0.5 ),
						new AnyFloatType( 1.5 ),
						new AnyFloatType( 2.5 ),
					]
				),
				new FloatTypeArray(
					[
						new AnyFloatType( 0.5 ),
						new AnyFloatType( 1.5 ),
						new AnyFloatType( 2.5 ),
					]
				),
				true,
			],
			[
				new FloatTypeArray(
					[
						new AnyFloatType( 0.5 ),
						new AnotherFloatType( 1.5 ),
						new AnyFloatType( 2.5 ),
					]
				),
				new FloatTypeArray(
					[
						new AnyFloatType( 0.5 ),
						new AnotherFloatType( 1.5 ),
						new AnyFloatType( 2.5 ),
					]
				),
				true,
			],
			[
				new FloatTypeArray(
					[
						new AnyFloatType( 0.5 ),
						new AnotherFloatType( 1.5 ),
						new AnyFloatType( 2.5 ),
					]
				),
				new FloatTypeArray(
					[
						new AnotherFloatType( 1.5 ),
						new AnyFloatType( 0.5 ),
						new AnyFloatType( 2.5 ),
					]
				),
				true,
			],
			[
				new FloatTypeArray(
					[
						new AnyFloatType( 0.5 ),
						new AnotherFloatType( 1.5 ),
						new AnyFloatType( 2.5 ),
					]
				),
				new FloatTypeArray(
					[
						new AnyFloatType( 0.5 ),
						new AnotherFloatType( 1.5 ),
						new AnyFloatType( 0.5 ),
					]
				),
				false,
			],
			[

				new FloatTypeArray(
					[
						new AnyFloatType( 0.5 ),
						new AnotherFloatType( 1.5 ),
						new AnyFloatType( 2.5 ),
					]
				),
				new FloatTypeArray(
					[
						new AnyFloatType( 0.5 ),
						new AnotherFloatType( 1.5 ),
						new AnotherFloatType( 2.5 ),
					]
				),
				false,
			],
		];
	}

	/**
	 * @dataProvider EqualsValueDataProvider
	 *
	 * @param FloatTypeArray $floatTypes
	 * @param FloatTypeArray $otherFloatTypes
	 * @param bool           $expectedResult
	 */
	public function testIfEqualsValueComparesOnlyValues( FloatTypeArray $floatTypes, FloatTypeArray $otherFloatTypes, bool $expectedResult ): void
	{
		self::assertSame( $expectedResult, $floatTypes->equalsValues( $otherFloatTypes ) );
	}

	public function ToJsonDataProvider(): array
	{
		return [
			[
				new FloatTypeArray( [ new AnyFloatType( 0.5 ), new AnotherFloatType( 1.5 ), new AnyFloatType( 2.5 ), ] ),
				json_encode( [ 0.5, 1.5, 2.5, ], JSON_THROW_ON_ERROR ),
			],
		];
	}

	public function testToJson(): void
	{
		$floatTypes   = new FloatTypeArray( [ new AnyFloatType( 0.5 ), new AnotherFloatType( 1.5 ), new AnyFloatType( 2.5 ), ] );
		$expectedJson = json_encode( [ 0.5, 1.5, 2.5, ], JSON_THROW_ON_ERROR );

		self::assertSame( $expectedJson, $floatTypes->toJson() );
	}

	public function testCountingElements(): void
	{
		self::assertCount( 0, new FloatTypeArray( [] ) );
		self::assertCount( 1, new FloatTypeArray( [ new AnyFloatType( 0.5 ) ] ) );
		self::assertCount( 1, new FloatTypeArray( [ '0.5' => new AnyFloatType( 0.5 ) ] ) );
		self::assertCount( 2, new FloatTypeArray( [ new AnyFloatType( 0.5 ), new AnotherFloatType( 0.5 ) ] ) );
		self::assertCount( 3, new FloatTypeArray( [ new AnyFloatType( 0.5 ), new AnotherFloatType( 1.5 ), new AnotherFloatType( 2.5 ) ] ) );
	}

	public function testOffsetMethods(): void
	{
		$floatTypes      = new FloatTypeArray( [] );
		$floatTypes[0.5] = new AnyFloatType( 1.5 );

		self::assertEquals( new AnyFloatType( 1.5 ), $floatTypes[0.5] );

		unset( $floatTypes[1.5] );

		self::assertNotContains( 0.5, $floatTypes );
	}

	public function testIterationMethod(): void
	{
		$floatTypes = new FloatTypeArray(
			[
				'first'  => new AnyFloatType( 0.5 ),
				'second' => new AnotherFloatType( 1.5 ),
				'last'   => new AnyFloatType( 2.5 ),
			]
		);

		self::assertEquals( new AnyFloatType( 0.5 ), $floatTypes->current() );
		self::assertEquals( 'first', $floatTypes->key() );
		self::assertEquals( true, $floatTypes->valid() );

		$floatTypes->next();

		self::assertEquals( new AnotherFloatType( 1.5 ), $floatTypes->current() );
		self::assertEquals( 'second', $floatTypes->key() );
		self::assertEquals( true, $floatTypes->valid() );

		$floatTypes->next();

		self::assertEquals( new AnyFloatType( 2.5 ), $floatTypes->current() );
		self::assertEquals( 'last', $floatTypes->key() );
		self::assertEquals( true, $floatTypes->valid() );

		$floatTypes->rewind();

		self::assertEquals( new AnyFloatType( 0.5 ), $floatTypes->current() );
		self::assertEquals( 'first', $floatTypes->key() );
		self::assertEquals( true, $floatTypes->valid() );
	}

	public function testIfAnotherFloatTypeArrayAllowsOnlyAnyFloatType(): void
	{
		$this->expectException( ValidationException::class );

		new class([ new AnyFloatType( 0.5 ), new AnotherFloatType( 1.5 ) ]) extends AbstractFloatTypeArray {
			protected static function isValid( RepresentsFloatType $floatType ): bool
			{
				return $floatType instanceof AnyFloatType;
			}
		};
	}

	public function testIfAllowedTypeDoesNotThrowException(): void
	{
		$this->expectNotToPerformAssertions();

		new class([ new AnyFloatType( 0.5 ), new AnyFloatType( 1.5 ) ]) extends AbstractFloatTypeArray {
			protected static function isValid( RepresentsFloatType $floatType ): bool
			{
				return $floatType instanceof AnyFloatType;
			}
		};
	}

	public function testInitializeClassUsingTrait(): void
	{
		$this->expectNotToPerformAssertions();

		new class([ new AnyFloatType( 0.5 ), new AnyFloatType( 1.5 ) ]) {
			use RepresentingFloatArrayType;
		};
	}
}
