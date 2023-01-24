<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit;

use Fortuneglobe\Types\Helpers\TypesToArrayHelper;
use Fortuneglobe\Types\StringTypeArray;
use Fortuneglobe\Types\Tests\Unit\Samples\AnotherFloatType;
use Fortuneglobe\Types\Tests\Unit\Samples\AnotherIntType;
use Fortuneglobe\Types\Tests\Unit\Samples\AnotherStringType;
use Fortuneglobe\Types\Tests\Unit\Samples\AnyFloatType;
use Fortuneglobe\Types\Tests\Unit\Samples\AnyStringType;
use Fortuneglobe\Types\Tests\Unit\Samples\JustAnIntType;
use Fortuneglobe\Types\Tests\Unit\Samples\RandomStringableType;
use PHPUnit\Framework\TestCase;

class TypesToArrayHelperTest extends TestCase
{
	public function testStringTypesToRawStringArray(): void
	{
		$expected = [
			'one',
			'two',
			'three',
		];

		$types = [
			new AnyStringType( 'one' ),
			new AnyStringType( 'two' ),
			new AnotherStringType( 'three' ),
		];

		self::assertEquals( $expected, TypesToArrayHelper::toStringArray( $types ) );
	}

	public function testIntTypesToRawStringArray(): void
	{
		$expected = [
			'1',
			'2',
			'3',
		];

		$types = [
			new JustAnIntType( 1 ),
			new JustAnIntType( 2 ),
			new AnotherIntType( 3 ),
		];

		self::assertEquals( $expected, TypesToArrayHelper::toStringArray( $types ) );
	}

	public function testFloatTypesToRawStringArray(): void
	{
		$expected = [
			'1.1',
			'2.2',
			'3.3',
		];

		$types = [
			new AnyFloatType( 1.1 ),
			new AnyFloatType( 2.2 ),
			new AnotherFloatType( 3.3 ),
		];

		self::assertEquals( $expected, TypesToArrayHelper::toStringArray( $types ) );
	}

	public function testStringablesToRawStringArray(): void
	{
		$expected = [
			'one',
			'two',
			'three',
		];

		$types = [
			new RandomStringableType( 'one' ),
			new RandomStringableType( 'two' ),
			new RandomStringableType( 'three' ),
		];

		self::assertEquals( $expected, TypesToArrayHelper::toStringArray( $types ) );
	}

	public function testIntTypesToRawIntArray(): void
	{
		$expected = [
			1,
			2,
			3,
		];

		$types = [
			new JustAnIntType( 1 ),
			new JustAnIntType( 2 ),
			new AnotherIntType( 3 ),
		];

		self::assertEquals( $expected, TypesToArrayHelper::toIntArray( $types ) );
	}

	public function testFloatTypesToRawFloatArray(): void
	{
		$expected = [
			1.1,
			2.2,
			3.3,
		];

		$types = [
			new AnyFloatType( 1.1 ),
			new AnyFloatType( 2.2 ),
			new AnotherFloatType( 3.3 ),
		];

		self::assertEquals( $expected, TypesToArrayHelper::toFloatArray( $types ) );
	}

	public function testIntTypesToRawFloatArray(): void
	{
		$expected = [
			1.0,
			2.0,
			3.0,
		];

		$types = [
			new JustAnIntType( 1 ),
			new JustAnIntType( 2 ),
			new AnotherIntType( 3 ),
		];

		self::assertEquals( $expected, TypesToArrayHelper::toFloatArray( $types ) );
	}

	public function testArrayTypesToRawArrayArray(): void
	{
		$expected = [
			[
				'one',
				'two',
				'three',
			],
			[
				'four',
				'five',
				'six',
			],
		];

		$types = [
			new StringTypeArray(
				[
					new AnyStringType( 'one' ),
					new AnyStringType( 'two' ),
					new AnotherStringType( 'three' ),
				]
			),
			new StringTypeArray(
				[
					new AnyStringType( 'four' ),
					new AnyStringType( 'five' ),
					new AnotherStringType( 'six' ),
				]
			),
		];

		self::assertEquals( $expected, TypesToArrayHelper::toArrayArray( $types ) );
	}
}
