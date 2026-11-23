<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit;

use Fortuneglobe\Types\Exceptions\ValidationException;
use Fortuneglobe\Types\Interfaces\RepresentsStringType;
use Fortuneglobe\Types\Tests\Unit\Samples\AnotherStringType;
use Fortuneglobe\Types\Tests\Unit\Samples\AnotherUuid4Type;
use Fortuneglobe\Types\Tests\Unit\Samples\AnyStringType;
use Fortuneglobe\Types\Uuid4;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class Uuid4Test extends TestCase
{
	public function testToString(): void
	{
		self::assertSame( 'e403623c-934f-4b12-84ee-f358ac3291ba', (new Uuid4( 'e403623c-934f-4b12-84ee-f358ac3291ba' ))->toString() );
		self::assertSame( 'e403623c-934f-4b12-84ee-f358ac3291ba', (string)new Uuid4( 'e403623c-934f-4b12-84ee-f358ac3291ba' ) );
	}

	public static function InvalidUuid4Provider(): array
	{
		return [
			[ 'c2667c9b-01b7-48e0-bb16-1df24837ec3f2', ],
			[ 'c2667c9b-01b7-48e0-b-1df24837ec3f', ],
			[ 'c2667c9b-01b7-48e0--1df24837ec3f', ],
			[ 'c2667c9b-01b7-48e0-1df24837ec3f', ],
			[ 'invalid', ],
		];
	}

	/**
	 * @param string $invalidValue
	 */
	#[DataProvider( 'InvalidUuid4Provider' )]
	public function testIfInvalidValueThrowsException( string $invalidValue ): void
	{
		$this->expectException( ValidationException::class );
		$this->expectExceptionMessage( 'Invalid Uuid4: ' . $invalidValue );

		new Uuid4( $invalidValue );
	}

	public static function ValidUuid4Provider(): array
	{
		return [
			[ 'c2667c9b-01b7-48e0-bb16-1df24837ec3f', ],
			[ '00000000-0000-0000-0000-000000000000', ],
		];
	}

	/**
	 * @param string $validValue
	 */
	#[DataProvider( 'ValidUuid4Provider' )]
	public function testIfValidValueThrowsException( string $validValue ): void
	{
		$this->expectNotToPerformAssertions();

		new Uuid4( $validValue );
	}

	public function testGeneratingUuid4(): void
	{
		$uuid4 = Uuid4::generate();

		self::assertTrue( preg_match( '!^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$!i', $uuid4->toString() ) > 0 );
		self::assertSame( Uuid4::class, get_class( $uuid4 ) );
	}

	public static function EqualsDataProvider(): array
	{
		return [
			[
				new Uuid4( '4dce17db-3031-4de2-b428-962952e2166b' ),
				new Uuid4( '4dce17db-3031-4de2-b428-962952e2166b' ),
				true,
			],
			[
				new AnotherUuid4Type( '4dce17db-3031-4de2-b428-962952e2166b' ),
				new Uuid4( '4dce17db-3031-4de2-b428-962952e2166b' ),
				false,
			],
			[
				new Uuid4( '4dce17db-3031-4de2-b428-962952e2166b' ),
				new Uuid4( 'f1025ba6-bcaf-4257-8e48-58ebc96788b4' ),
				false,
			],
			[
				new AnotherUuid4Type( '4dce17db-3031-4de2-b428-962952e2166b' ),
				new Uuid4( '605879b6-14ae-4323-9994-7bcd6a53bb90' ),
				false,
			],
		];
	}

	/**
	 * @param RepresentsStringType $uuid4
	 * @param RepresentsStringType $anotherUuid4
	 * @param bool                 $expectedResult
	 *
	 * @return void
	 */
	#[DataProvider( 'EqualsDataProvider' )]
	public function testEquals( RepresentsStringType $uuid4, RepresentsStringType $anotherUuid4, bool $expectedResult ): void
	{
		self::assertEquals( $expectedResult, $uuid4->equals( $anotherUuid4 ) );
	}

	public static function EqualsValueDataProvider(): array
	{
		return [
			[
				new Uuid4( '4dce17db-3031-4de2-b428-962952e2166b' ),
				new Uuid4( '4dce17db-3031-4de2-b428-962952e2166b' ),
				true,
			],
			[
				new AnotherUuid4Type( '4dce17db-3031-4de2-b428-962952e2166b' ),
				new Uuid4( '4dce17db-3031-4de2-b428-962952e2166b' ),
				true,
			],
			[
				new AnyStringType( '4dce17db-3031-4de2-b428-962952e2166b' ),
				new Uuid4( '4dce17db-3031-4de2-b428-962952e2166b' ),
				true,
			],
			[
				new Uuid4( '4dce17db-3031-4de2-b428-962952e2166b' ),
				new Uuid4( 'f1025ba6-bcaf-4257-8e48-58ebc96788b4' ),
				false,
			],
			[
				new AnotherUuid4Type( '4dce17db-3031-4de2-b428-962952e2166b' ),
				new Uuid4( '605879b6-14ae-4323-9994-7bcd6a53bb90' ),
				false,
			],
		];
	}

	/**
	 * @param RepresentsStringType $uuid4
	 * @param RepresentsStringType $anotherUuid4
	 * @param bool                 $expectedResult
	 *
	 * @return array[]
	 */
	#[DataProvider( 'EqualsValueDataProvider' )]
	public function testEqualsValue( RepresentsStringType $uuid4, RepresentsStringType $anotherUuid4, bool $expectedResult ): void
	{
		self::assertEquals( $expectedResult, $uuid4->equalsValue( $anotherUuid4 ) );
	}
}
