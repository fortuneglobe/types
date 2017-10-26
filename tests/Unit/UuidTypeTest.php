<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit;

use Fortuneglobe\Types\Exceptions\InvalidUuidValueException;
use Fortuneglobe\Types\Interfaces\RepresentsScalarValue;
use Fortuneglobe\Types\Interfaces\RepresentsUuidValue;
use Fortuneglobe\Types\Tests\Unit\Fixtures\TestUuid;
use Fortuneglobe\Types\UuidType;
use PHPUnit\Framework\TestCase;

/**
 * Class UuidTypeTest
 * @package Fortuneglobe\Types\Tests\Unit
 */
final class UuidTypeTest extends TestCase
{
	public function testCanGenerateUuidType() : void
	{
		$uuid = TestUuid::generate();

		$this->assertInstanceOf( RepresentsScalarValue::class, $uuid );
		$this->assertInstanceOf( RepresentsUuidValue::class, $uuid );
		$this->assertInstanceOf( UuidType::class, $uuid );
	}

	/**
	 * @param string $uuidString
	 *
	 * @dataProvider uuidStringProvider
	 */
	public function testCanConstructUuidType( string $uuidString )
	{
		$uuid = new TestUuid( $uuidString );

		$this->assertInstanceOf( RepresentsScalarValue::class, $uuid );
		$this->assertInstanceOf( RepresentsUuidValue::class, $uuid );
		$this->assertInstanceOf( UuidType::class, $uuid );
	}

	public function uuidStringProvider()
	{
		return [
			[
				'uuidString' => '00000000-0000-0000-0000-000000000000',
			],
			[
				'uuidString' => '12345678-1234-1234-1234-123456789012',
			],
			[
				'uuidString' => TestUuid::generate()->toString(),
			],
		];
	}

	/**
	 * @param RepresentsUuidValue $UuidTypeA
	 * @param RepresentsUuidValue $UuidTypeB
	 *
	 * @dataProvider equalUuidsProvider
	 */
	public function testCanCheckForEuqality( RepresentsUuidValue $UuidTypeA, RepresentsUuidValue $UuidTypeB )
	{
		$this->assertTrue( $UuidTypeA->equals( $UuidTypeB ) );
		$this->assertTrue( $UuidTypeB->equals( $UuidTypeA ) );
	}

	public function equalUuidsProvider()
	{
		return [
			[
				'UuidTypeA' => new TestUuid( '12345678-1234-1234-1234-123456789012' ),
				'UuidTypeB' => new TestUuid( '12345678-1234-1234-1234-123456789012' ),
			],
		];
	}

	/**
	 * @param RepresentsUuidValue $UuidTypeA
	 * @param RepresentsUuidValue $UuidTypeB
	 *
	 * @dataProvider notEqualUuidsProvider
	 */
	public function testCanCheckThatUuidTypesAreNotEqual(
		RepresentsUuidValue $UuidTypeA,
		RepresentsUuidValue $UuidTypeB
	)
	{
		$this->assertFalse( $UuidTypeA->equals( $UuidTypeB ) );
		$this->assertFalse( $UuidTypeB->equals( $UuidTypeA ) );
	}

	public function notEqualUuidsProvider()
	{
		$uuidStringA      = '12345678-1234-1234-1234-123456789012';
		$uuidStringB      = '12345678-1234-1234-1234-210987654321';
		$extendedUuidType = new class($uuidStringA) extends UuidType
		{
		};

		return [
			# Different values
			[
				'UuidTypeA' => new TestUuid( $uuidStringA ),
				'UuidTypeB' => new TestUuid( $uuidStringB ),
			],
			# Different objects
			[
				'UuidTypeA' => new TestUuid( $uuidStringA ),
				'UuidTypeB' => $extendedUuidType,
			],
		];
	}

	public function testCanRepresentUuidTypeAsString()
	{
		$uuidString = '12345678-1234-1234-1234-123456789012';
		$uuid       = new TestUuid( $uuidString );
		$this->assertSame( $uuidString, $uuid->toString() );
		$this->assertSame( $uuidString, (string)$uuid );
	}

	/**
	 * @param RepresentsUuidValue $uuidentifier
	 * @param string              $expectedJson
	 *
	 * @dataProvider uuidToJsonProvider
	 */
	public function testCanSerializeUuidTypeAsJson( RepresentsUuidValue $uuidentifier, string $expectedJson )
	{
		$this->assertSame( $expectedJson, json_encode( $uuidentifier ) );
	}

	public function uuidToJsonProvider()
	{
		$generatedUuidentifier = TestUuid::generate();

		return [
			[
				'identifier'   => new TestUuid( '12345678-1234-1234-1234-123456789012' ),
				'expectedJson' => '"12345678-1234-1234-1234-123456789012"',
			],
			[
				'identifier'   => $generatedUuidentifier,
				'expectedJson' => sprintf( '"%s"', $generatedUuidentifier->toString() ),
			],
		];
	}

	public function testExpectExceptionIfInvalidUuidStringIsProvided()
	{
		try
		{
			new TestUuid( 'foobar' );
		}
		catch ( InvalidUuidValueException $ex )
		{
			$this->assertEquals( $ex->getUuid(), 'foobar' );
			$this->assertEquals( $ex->getMessage(), 'Invalid UUID provided: foobar' );
		}
	}
}
