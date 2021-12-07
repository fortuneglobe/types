<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit;

use Fortuneglobe\Types\AbstractDateType;
use Fortuneglobe\Types\Exceptions\ValidationException;
use Fortuneglobe\Types\Interfaces\RepresentsDateType;
use Fortuneglobe\Types\Tests\Unit\Samples\After2000DateType;
use Fortuneglobe\Types\Tests\Unit\Samples\AnotherDateType;
use Fortuneglobe\Types\Tests\Unit\Samples\AnyDateType;
use PHPUnit\Framework\TestCase;

class DateTypeTest extends TestCase
{
	public function testToString(): void
	{
		self::assertSame( '2021-08-04T21:15:37+02:00', (new AnyDateType( '2021-08-04 21:15:37', new \DateTimeZone( '+0200' ) ))->toString() );
		self::assertSame( '2021-08-04T21:15:37+02:00', (string)new AnyDateType( '2021-08-04 21:15:37', new \DateTimeZone( '+0200' ) ) );
	}

	public function testInvalidValueThrowsException(): void
	{
		$this->expectException( ValidationException::class );
		$this->expectExceptionMessage( 'Invalid After2000DateType: 1995-01-01 21:33:04' );

		new After2000DateType( '1995-01-01 21:33:04' );
	}

	public function testFromDateType(): void
	{
		self::assertEquals( new AnyDateType( '2021-08-04 21:15:37' ), AnyDateType::fromDateType( new AnotherDateType( '2021-08-04 21:15:37' ) ) );
	}

	public function testFromTimestamp(): void
	{
		self::assertEquals( new AnyDateType( '1970-01-01 01:00:00' ), AnyDateType::fromTimestamp( 0 ) );
	}

	public function testIfSameClassWithSameValueEquals()
	{
		self::assertTrue( (new AnyDateType( '2021-08-04 21:15:37' ))->equals( new AnyDateType( '2021-08-04 21:15:37' ) ) );
	}

	public function testIfSameClassWithAnotherValueIsNotEqual()
	{
		self::assertFalse( (new AnyDateType( '2021-08-04 21:15:37' ))->equals( new AnyDateType( '2021-08-04 21:15:38' ) ) );
	}

	public function testIfAnotherClassWithSameValueIsNotEqual()
	{
		self::assertFalse( (new AnyDateType( '2021-08-04 21:15:37' ))->equals( new AnotherDateType( '2021-08-04 21:15:37' ) ) );
	}

	public function testIfInvalidValueThrowsException(): void
	{
		$this->expectException( ValidationException::class );

		new class('2021-08-04 21:15:37') extends AbstractDateType
		{
			public function isValid( \DateTimeInterface $value ): bool
			{
				return false;
			}
		};
	}

	public function testIfValidValueDoesNotThrowException(): void
	{
		$this->expectNotToPerformAssertions();

		new class('2021-08-04 21:15:37') extends AbstractDateType
		{
			public function isValid( \DateTimeInterface $value ): bool
			{
				return true;
			}
		};
	}

	public function testIfInvalidDateTimeThrowsException(): void
	{
		$invalidDateTime = 'invalid';

		$this->expectException( ValidationException::class );
		$this->expectExceptionMessage( 'Invalid AnyDateType: ' . $invalidDateTime );

		new AnyDateType( $invalidDateTime );
	}

	public function EqualsValueDataProvider(): array
	{
		return [
			[
				new AnyDateType( '2021-08-04 20:57:48' ),
				new AnotherDateType( '2021-08-04 20:57:48' ),
				true,
			],
			[
				new AnyDateType( '2021-08-04 20:57:48', new \DateTimeZone( '+0100' ) ),
				new AnotherDateType( '2021-08-04 20:57:48', new \DateTimeZone( '+0100' ) ),
				true,
			],
			[
				new AnyDateType( '2021-08-04 20:57:48', new \DateTimeZone( '+0100' ) ),
				new AnotherDateType( '2021-08-04 20:57:48', new \DateTimeZone( '+0200' ) ),
				false,
			],
			[
				new AnyDateType( '2021-08-04 20:57:48', new \DateTimeZone( '+0100' ) ),
				new AnotherDateType( '2021-08-04 20:57:49', new \DateTimeZone( '+0100' ) ),
				false,
			],
		];
	}

	/**
	 * @dataProvider EqualsValueDataProvider
	 *
	 * @param RepresentsDateType $dateType
	 * @param RepresentsDateType $anotherDateType
	 * @param bool               $expectedResult
	 */
	public function testIfEqualsValueComparesOnlyValues(
		RepresentsDateType $dateType, RepresentsDateType $anotherDateType, bool $expectedResult
	): void
	{
		self::assertSame( $expectedResult, $dateType->equalsValue( $anotherDateType ) );
	}
}
