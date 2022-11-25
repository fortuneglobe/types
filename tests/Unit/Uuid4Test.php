<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit;

use Fortuneglobe\Types\Exceptions\ValidationException;
use Fortuneglobe\Types\Interfaces\RepresentsStringType;
use Fortuneglobe\Types\Tests\Unit\Samples\AnotherStringType;
use Fortuneglobe\Types\Tests\Unit\Samples\AnotherUuid4Type;
use Fortuneglobe\Types\Tests\Unit\Samples\AnyStringType;
use Fortuneglobe\Types\Uuid4;
use PHPUnit\Framework\TestCase;

class Uuid4Test extends TestCase
{
	public function testToString(): void
	{
		self::assertSame( 'e403623c-934f-4b12-84ee-f358ac3291ba', Uuid4::fromString( 'e403623c-934f-4b12-84ee-f358ac3291ba' )->toString() );
		self::assertSame( 'e403623c-934f-4b12-84ee-f358ac3291ba', (string)Uuid4::fromString( 'e403623c-934f-4b12-84ee-f358ac3291ba' ) );
	}

	public function InvalidUuid4Provider(): array
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
	 * @dataProvider InvalidUuid4Provider
	 *
	 * @param string $invalidValue
	 */
	public function testIfInvalidValueThrowsException( string $invalidValue ): void
	{
		$this->expectException( ValidationException::class );
		$this->expectExceptionMessage( 'Invalid uuid: ' . $invalidValue );

		Uuid4::fromString( $invalidValue );
	}

	public function ValidUuid4Provider(): array
	{
		return [
			[ 'c2667c9b-01b7-48e0-bb16-1df24837ec3f', ],
			[ '00000000-0000-0000-0000-000000000000', ],
			[ new AnyStringType( 'c2667c9b-01b7-48e0-bb16-1df24837ec3f' ), ],
			[ new AnotherStringType( '00000000-0000-0000-0000-000000000000' ), ],
		];
	}

	/**
	 * @dataProvider ValidUuid4Provider
	 *
	 * @param RepresentsStringType|string $validValue
	 */
	public function testIfValidValueThrowsException( RepresentsStringType|string $validValue ): void
	{
		$this->expectNotToPerformAssertions();

		Uuid4::fromString( $validValue );
	}

	public function testGeneratingUuid4(): void
	{
		$uuid4 = Uuid4::generate();

		self::assertTrue( preg_match( '!^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$!i', $uuid4->toString() ) > 0 );
		self::assertSame( Uuid4::class, get_class( $uuid4 ) );
	}

	public function EqualsDataProvider(): array
	{
		return [
			[
				Uuid4::fromString( '4dce17db-3031-4de2-b428-962952e2166b' ),
				Uuid4::fromString( '4dce17db-3031-4de2-b428-962952e2166b' ),
				true,
			],
			[
				AnotherUuid4Type::fromString( '4dce17db-3031-4de2-b428-962952e2166b' ),
				Uuid4::fromString( '4dce17db-3031-4de2-b428-962952e2166b' ),
				false,
			],
			[
				Uuid4::fromString( '4dce17db-3031-4de2-b428-962952e2166b' ),
				Uuid4::fromString( 'f1025ba6-bcaf-4257-8e48-58ebc96788b4' ),
				false,
			],
			[
				AnotherUuid4Type::fromString( '4dce17db-3031-4de2-b428-962952e2166b' ),
				Uuid4::fromString( '605879b6-14ae-4323-9994-7bcd6a53bb90' ),
				false,
			],
		];
	}

	/**
	 * @dataProvider EqualsDataProvider
	 *
	 * @param RepresentsStringType $uuid4
	 * @param RepresentsStringType $anotherUuid4
	 * @param bool                 $expectedResult
	 *
	 * @return void
	 */
	public function testEquals( RepresentsStringType $uuid4, RepresentsStringType $anotherUuid4, bool $expectedResult ): void
	{
		self::assertEquals( $expectedResult, $uuid4->equals( $anotherUuid4 ) );
	}

	public function EqualsValueDataProvider(): array
	{
		return [
			[
				Uuid4::fromString( '4dce17db-3031-4de2-b428-962952e2166b' ),
				Uuid4::fromString( '4dce17db-3031-4de2-b428-962952e2166b' ),
				true,
			],
			[
				AnotherUuid4Type::fromString( '4dce17db-3031-4de2-b428-962952e2166b' ),
				Uuid4::fromString( '4dce17db-3031-4de2-b428-962952e2166b' ),
				true,
			],
			[
				new AnyStringType( '4dce17db-3031-4de2-b428-962952e2166b' ),
				Uuid4::fromString( '4dce17db-3031-4de2-b428-962952e2166b' ),
				true,
			],
			[
				Uuid4::fromString( '4dce17db-3031-4de2-b428-962952e2166b' ),
				Uuid4::fromString( 'f1025ba6-bcaf-4257-8e48-58ebc96788b4' ),
				false,
			],
			[
				AnotherUuid4Type::fromString( '4dce17db-3031-4de2-b428-962952e2166b' ),
				Uuid4::fromString( '605879b6-14ae-4323-9994-7bcd6a53bb90' ),
				false,
			],
		];
	}

	/**
	 * @dataProvider EqualsValueDataProvider
	 *
	 * @param RepresentsStringType $uuid4
	 * @param RepresentsStringType $anotherUuid4
	 * @param bool                 $expectedResult
	 *
	 * @return array[]
	 */
	public function testEqualsValue( RepresentsStringType $uuid4, RepresentsStringType $anotherUuid4, bool $expectedResult ): void
	{
		self::assertEquals( $expectedResult, $uuid4->equalsValue( $anotherUuid4 ) );
	}
}
