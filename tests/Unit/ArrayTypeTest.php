<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit;

use Fortuneglobe\Types\AbstractArrayType;
use Fortuneglobe\Types\Exceptions\ValidationException;
use Fortuneglobe\Types\Interfaces\RepresentsArrayType;
use Fortuneglobe\Types\Tests\Unit\Samples\AnotherArrayType;
use Fortuneglobe\Types\Tests\Unit\Samples\JustAnArrayType;
use Fortuneglobe\Types\Tests\Unit\Samples\NoNumberAsKeyArrayType;
use PHPUnit\Framework\TestCase;

class ArrayTypeTest extends TestCase
{
	public function testToArray(): void
	{
		$expectedArray = [
			'a' => 'abc',
			'b' => 1,
			'c' => [
				'def' => 3.25,
				'hij' => [
					'isTest' => true,
				],
			],
		];

		$arrayType = new JustAnArrayType( $expectedArray );

		self::assertSame( $expectedArray, $arrayType->toArray() );
	}

	public function testInvalidValueThrowsException(): void
	{
		$this->expectException( ValidationException::class );
		$this->expectExceptionMessage( 'Invalid NoNumberAsKeyArrayType: ' . print_r( [ '2' => 'test' ], true ) );

		new NoNumberAsKeyArrayType( [ '2' => 'test' ] );
	}

	public function testFromArrayType(): void
	{
		self::assertEquals( new JustAnArrayType( [ 'a' => 'b' ] ), JustAnArrayType::fromArrayType( new AnotherArrayType( [ 'a' => 'b' ] ) ) );
	}

	public function testIfSameClassWithSameValueEquals()
	{
		self::assertTrue( (new JustAnArrayType( [ 'a' => 'b' ] ))->equals( new JustAnArrayType( [ 'a' => 'b' ] ) ) );
	}

	public function testIfSameClassWithAnotherValueIsNotEqual()
	{
		self::assertFalse( (new JustAnArrayType( [ 'a' => 'b' ] ))->equals( new JustAnArrayType( [ 'a' => 'c' ] ) ) );
		self::assertFalse( (new JustAnArrayType( [ 'a' => 'b' ] ))->equals( new JustAnArrayType( [ 'b' => 'b' ] ) ) );
	}

	public function testIfAnotherClassWithSameValueIsNotEqual()
	{
		self::assertFalse( (new JustAnArrayType( [ 'a' => 'b' ] ))->equals( new AnotherArrayType( [ 'a' => 'b' ] ) ) );
	}

	public function testIfInvalidValueThrowsException(): void
	{
		$this->expectException( ValidationException::class );

		new class([ 'a' => 'b' ]) extends AbstractArrayType
		{
			public static function isValid( array $genericArray ): bool
			{
				return false;
			}
		};
	}

	public function testIfValidValueDoesNotThrowException(): void
	{
		$this->expectNotToPerformAssertions();

		new class([ 'a' => 'b' ]) extends AbstractArrayType
		{
			public static function isValid( array $genericArray ): bool
			{
				return true;
			}
		};
	}

	public function EqualsValueDataProvider(): array
	{
		return [
			[
				new JustAnArrayType(
					[
						'a' => 'b',
						'c' => [
							'd' => 'e',
							'f' => 'g',
							'h' => [
								'i' => 'j',
							],
						],
					]
				),
				new AnotherArrayType(
					[
						'a' => 'b',
						'c' => [
							'h' => [
								'i' => 'j',
							],
							'f' => 'g',
							'd' => 'e',
						],
					]
				),
				true,
			],
			[
				new JustAnArrayType(
					[
						'a' => 'b',
						'c' => [
							'd' => 'e',
							'f' => 'g',
							'h' => [
								'i' => 'j',
							],
						],
					]
				),
				new AnotherArrayType(
					[
						'c' => [
							'h' => [
								'i' => 'j',
							],
							'f' => 'g',
							'd' => 'e',
						],
						'a' => 'b',
					]
				),
				true,
			],
			[
				new JustAnArrayType(
					[
						'1' => 'a',
						'c' => [
							'd' => 'e',
							'f' => 'g',
							'h' => [
								'i' => 'j',
							],
						],
					]
				),
				new AnotherArrayType(
					[
						'c' => [
							'h' => [
								'i' => 'j',
							],
							'f' => 'g',
							'd' => 'e',
						],
						1   => 'a',
					]
				),
				true,
			],
			[
				new JustAnArrayType(
					[
						'a' => '1',
						'c' => [
							'd' => 'e',
							'f' => 'g',
							'h' => [
								'i' => 'j',
							],
						],
					]
				),
				new AnotherArrayType(
					[
						'c' => [
							'h' => [
								'i' => 'j',
							],
							'f' => 'g',
							'd' => 'e',
						],
						'a' => 1,
					]
				),
				false,
			],
			[
				new JustAnArrayType(
					[
						'1' => 'a',
						'c' => [
							'd' => 'e',
							'f' => 'g',
							'h' => [
								'i' => 'j',
							],
						],
					]
				),
				new AnotherArrayType(
					[
						'c' => [
							'h' => [
								'i' => 'j',
							],
							'f' => 'g',
							'd' => 'e',
						],
						1   => 'b',
					]
				),
				false,
			],
		];
	}

	/**
	 * @dataProvider EqualsValueDataProvider
	 *
	 * @param RepresentsArrayType $arrayType
	 * @param RepresentsArrayType $anotherArrayType
	 * @param bool                $expectedResult
	 */
	public function testIfEqualsValueComparesOnlyValues(
		RepresentsArrayType $arrayType, RepresentsArrayType $anotherArrayType, bool $expectedResult
	): void
	{
		self::assertSame( $expectedResult, $arrayType->equalsValue( $anotherArrayType ) );
	}

	public function ToJsonDataProvider(): array
	{
		return [
			[
				new JustAnArrayType(
					[
						'a' => 'abc',
						'b' => 1,
						'c' => [
							'def' => 3.25,
							'hij' => [
								'isTest' => true,
							],
						],
					]
				),
				json_encode(
					[
						'a' => 'abc',
						'b' => 1,
						'c' => [
							'def' => 3.25,
							'hij' => [
								'isTest' => true,
							],
						],
					],
					JSON_THROW_ON_ERROR
				),
			],
		];
	}

	public function testToJson(): void
	{
		$data         = [
			'a' => 'abc',
			'b' => 1,
			'c' => [
				'def' => 3.25,
				'hij' => [
					'isTest' => true,
				],
			],
		];
		$expectedJson = json_encode( $data, JSON_THROW_ON_ERROR );

		self::assertSame( $expectedJson, (new JustAnArrayType( $data ))->toJson() );
		self::assertSame( $expectedJson, json_encode( new JustAnArrayType( $data ), JSON_THROW_ON_ERROR ) );
	}

	public function testFromJson(): void
	{
		$data = [
			'a' => 'abc',
			'b' => 1,
			'c' => [
				'def' => 3.25,
				'hij' => [
					'isTest' => true,
				],
			],
		];

		$expectedType = new JustAnArrayType( $data );

		self::assertEquals( $expectedType, JustAnArrayType::fromJson( json_encode( $data, JSON_THROW_ON_ERROR ) ) );
	}

	public function testCountingElements(): void
	{
		self::assertCount( 0, new JustAnArrayType( [] ) );
		self::assertCount( 1, new JustAnArrayType( [ 0 => 'a' ] ) );
		self::assertCount( 1, new JustAnArrayType( [ 'a' => 1 ] ) );
		self::assertCount( 2, new JustAnArrayType( [ 'a' => 1, 'b' => [ 'a', 'b', 'c' ] ] ) );
		self::assertCount( 3, new JustAnArrayType( [ 'a', 'b', 'c' ] ) );
	}

	public function testOffsetMethods(): void
	{
		$arrayType      = new JustAnArrayType( [] );
		$arrayType['a'] = 'b';

		self::assertEquals( 'b', $arrayType['a'] );

		unset( $arrayType['b'] );

		self::assertNotContains( 'a', $arrayType );
	}

	public function testIterationMethod(): void
	{
		$arrayType = new JustAnArrayType(
			[
				'first'  => 'a',
				'second' => 'b',
				'last'   => 'c',
			]
		);

		self::assertEquals( 'a', $arrayType->current() );
		self::assertEquals( 'first', $arrayType->key() );
		self::assertEquals( true, $arrayType->valid() );

		$arrayType->next();

		self::assertEquals( 'b', $arrayType->current() );
		self::assertEquals( 'second', $arrayType->key() );
		self::assertEquals( true, $arrayType->valid() );

		$arrayType->next();

		self::assertEquals( 'c', $arrayType->current() );
		self::assertEquals( 'last', $arrayType->key() );
		self::assertEquals( true, $arrayType->valid() );

		$arrayType->rewind();

		self::assertEquals( 'a', $arrayType->current() );
		self::assertEquals( 'first', $arrayType->key() );
		self::assertEquals( true, $arrayType->valid() );
	}
}
