<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit;

use Fortuneglobe\Types\AbstractDateType;
use Fortuneglobe\Types\Exceptions\ValidationException;
use Fortuneglobe\Types\Interfaces\RepresentsDateType;
use Fortuneglobe\Types\Tests\Unit\Samples\After2000DateType;
use Fortuneglobe\Types\Tests\Unit\Samples\AnotherDateType;
use Fortuneglobe\Types\Tests\Unit\Samples\AnyDateType;
use Fortuneglobe\Types\Traits\RepresentingDateType;
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
		self::assertEquals( new AnyDateType( '2021-08-04 21:15:37' ), AnyDateType::fromDate( new AnotherDateType( '2021-08-04 21:15:37' ) ) );
		self::assertEquals( new AnyDateType( '2021-08-04 21:15:37' ), AnyDateType::fromDate( new \DateTimeImmutable( '2021-08-04 21:15:37' ) ) );
		self::assertEquals( new AnyDateType( '2021-08-04 21:15:37' ), AnyDateType::fromDate( new \DateTime( '2021-08-04 21:15:37' ) ) );
	}

	public function testFromTimestamp(): void
	{
		self::assertEquals( new AnyDateType( '1970-01-01 00:00:00', new \DateTimeZone( '+0000' ) ), AnyDateType::fromTimestamp( 0, new \DateTimeZone( '+0000' ) ) );
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

		new class('2021-08-04 21:15:37') extends AbstractDateType {
			public static function isValid( \DateTimeInterface $value ): bool
			{
				return false;
			}
		};
	}

	public function testIfValidValueDoesNotThrowException(): void
	{
		$this->expectNotToPerformAssertions();

		new class('2021-08-04 21:15:37') extends AbstractDateType {
			public static function isValid( \DateTimeInterface $value ): bool
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
	public function testIfEqualsValueComparesOnlyValues( RepresentsDateType $dateType, RepresentsDateType $anotherDateType, bool $expectedResult ): void
	{
		self::assertSame( $expectedResult, $dateType->equalsValue( $anotherDateType ) );
	}

	public function testInitializeClassUsingTrait(): void
	{
		$dateType = new class('2021-08-04 20:57:48') {
			use RepresentingDateType;
		};

		self::assertEquals( '20210804', $dateType->format( 'Ymd' ) );
	}

	public function testJsonSerialize(): void
	{
		$date = new AnyDateType( '2023-03-01 07:33:35' );

		self::assertSame( '"2023-03-01 07:33:35"', json_encode( $date, JSON_THROW_ON_ERROR ) );
	}

	public function testAdd(): void
	{
		self::assertEquals( new AnyDateType( '2023-03-01 07:30:00' ), (new AnyDateType( '2023-03-01 04:30:00' ))->add( new \DateInterval( 'PT3H' ) ) );
		self::assertEquals( new AnyDateType( '2023-03-04 07:30:00' ), (new AnyDateType( '2023-03-01 07:30:00' ))->add( new \DateInterval( 'P3D' ) ) );
	}

	public function testSub(): void
	{
		self::assertEquals( new AnyDateType( '2023-03-01 07:30:00' ), (new AnyDateType( '2023-03-01 10:30:00' ))->sub( new \DateInterval( 'PT3H' ) ) );
		self::assertEquals( new AnyDateType( '2023-03-01 07:30:00' ), (new AnyDateType( '2023-03-04 07:30:00' ))->sub( new \DateInterval( 'P3D' ) ) );
	}

	public function testIsGreaterThan(): void
	{
		$dateTime = new AnyDateType( '2023-03-01 07:30:00' );

		self::assertTrue( $dateTime->isGreaterThan( new AnyDateType( '2023-03-01 07:29:59' ) ) );
		self::assertFalse( $dateTime->isGreaterThan( new AnyDateType( '2023-03-01 07:30:00' ) ) );
		self::assertFalse( $dateTime->isGreaterThan( new AnyDateType( '2023-03-01 07:30:01' ) ) );
		self::assertTrue( $dateTime->isGreaterThan( new \DateTimeImmutable( '2023-03-01 07:29:59' ) ) );
		self::assertTrue( $dateTime->isGreaterThan( new \DateTime( '2023-03-01 07:29:59' ) ) );
		self::assertTrue( $dateTime->isGreaterThan( new AnotherDateType( '2023-03-01 07:29:59' ) ) );
		self::assertTrue( $dateTime->isGreaterThan( '2023-03-01 07:29:59' ) );
	}

	public function testIsGreaterThanOrEqual(): void
	{
		$dateTime = new AnyDateType( '2023-03-01 07:30:00' );

		self::assertTrue( $dateTime->isGreaterThanOrEqual( new AnyDateType( '2023-03-01 07:29:59' ) ) );
		self::assertTrue( $dateTime->isGreaterThanOrEqual( new AnyDateType( '2023-03-01 07:30:00' ) ) );
		self::assertFalse( $dateTime->isGreaterThanOrEqual( new AnyDateType( '2023-03-01 07:30:01' ) ) );
		self::assertTrue( $dateTime->isGreaterThanOrEqual( new \DateTimeImmutable( '2023-03-01 07:29:59' ) ) );
		self::assertTrue( $dateTime->isGreaterThanOrEqual( new \DateTime( '2023-03-01 07:29:59' ) ) );
		self::assertTrue( $dateTime->isGreaterThanOrEqual( new AnotherDateType( '2023-03-01 07:29:59' ) ) );
		self::assertTrue( $dateTime->isGreaterThanOrEqual( '2023-03-01 07:29:59' ) );
	}

	public function testIsLessThan(): void
	{
		$dateTime = new AnyDateType( '2023-03-01 07:30:00' );

		self::assertTrue( $dateTime->isLessThan( new AnyDateType( '2023-03-01 07:30:01' ) ) );
		self::assertFalse( $dateTime->isLessThan( new AnyDateType( '2023-03-01 07:30:00' ) ) );
		self::assertFalse( $dateTime->isLessThan( new AnyDateType( '2023-03-01 07:29:59' ) ) );
		self::assertTrue( $dateTime->isLessThan( new \DateTimeImmutable( '2023-03-01 07:30:01' ) ) );
		self::assertTrue( $dateTime->isLessThan( new \DateTime( '2023-03-01 07:30:01' ) ) );
		self::assertTrue( $dateTime->isLessThan( new AnotherDateType( '2023-03-01 07:30:01' ) ) );
		self::assertTrue( $dateTime->isLessThan( '2023-03-01 07:30:01' ) );
	}

	public function testIsLessThanOrEqual(): void
	{
		$dateTime = new AnyDateType( '2023-03-01 07:30:00' );

		self::assertTrue( $dateTime->isLessThanOrEqual( new AnyDateType( '2023-03-01 07:30:01' ) ) );
		self::assertTrue( $dateTime->isLessThanOrEqual( new AnyDateType( '2023-03-01 07:30:00' ) ) );
		self::assertFalse( $dateTime->isLessThanOrEqual( new AnyDateType( '2023-03-01 07:29:59' ) ) );
		self::assertTrue( $dateTime->isLessThanOrEqual( new \DateTimeImmutable( '2023-03-01 07:30:01' ) ) );
		self::assertTrue( $dateTime->isLessThanOrEqual( new \DateTime( '2023-03-01 07:30:01' ) ) );
		self::assertTrue( $dateTime->isLessThanOrEqual( new AnotherDateType( '2023-03-01 07:30:01' ) ) );
		self::assertTrue( $dateTime->isLessThanOrEqual( '2023-03-01 07:30:01' ) );
	}

	public function testHasExpired(): void
	{
		$dateTime = new AnyDateType( '2023-03-01 07:30:00' );

		self::assertTrue( $dateTime->hasExpired( null, new \DateTimeImmutable( '2023-03-01 07:30:01' ) ) );
		self::assertFalse( $dateTime->hasExpired( null, new \DateTimeImmutable( '2023-03-01 07:30:00' ) ) );
		self::assertFalse( $dateTime->hasExpired( null, new \DateTimeImmutable( '2023-03-01 07:29:59' ) ) );
		self::assertTrue( $dateTime->hasExpired( new \DateInterval( 'PT15M' ), new \DateTimeImmutable( '2023-03-01 07:45:01' ) ) );
		self::assertFalse( $dateTime->hasExpired( new \DateInterval( 'PT15M' ), new \DateTimeImmutable( '2023-03-01 07:45:00' ) ) );
		self::assertFalse( $dateTime->hasExpired( new \DateInterval( 'PT15M' ), new \DateTimeImmutable( '2023-03-01 07:44:59' ) ) );
	}
}
