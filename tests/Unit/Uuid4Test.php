<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit;

use Fortuneglobe\Types\Exceptions\ValidationException;
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
			[
				'c2667c9b-01b7-48e0-bb16-1df24837ec3f2',
				'c2667c9b-01b7-48e0-b-1df24837ec3f',
				'c2667c9b-01b7-48e0--1df24837ec3f',
				'c2667c9b-01b7-48e0-1df24837ec3f',
				'invalid',
			],
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
			[
				'c2667c9b-01b7-48e0-bb16-1df24837ec3f',
				'00000000-0000-0000-0000-000000000000',
			],
		];
	}

	/**
	 * @dataProvider ValidUuid4Provider
	 *
	 * @param string $validValue
	 */
	public function testIfValidValueThrowsException( string $validValue ): void
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

}
