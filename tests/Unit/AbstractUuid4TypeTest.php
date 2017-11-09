<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit;

use Fortuneglobe\Types\AbstractUuid4Type;
use Fortuneglobe\Types\Exceptions\InvalidUuid4ValueException;
use Fortuneglobe\Types\Interfaces\RepresentsScalarValue;
use Fortuneglobe\Types\Interfaces\RepresentsUuid4Value;
use Fortuneglobe\Types\Tests\Unit\Fixtures\TestUuid4;
use PHPUnit\Framework\TestCase;

/**
 * Class UuidTypeTest
 * @package Fortuneglobe\Types\Tests\Unit
 */
final class AbstractUuid4TypeTest extends TestCase
{
	public function testCanGenerateUuidType() : void
	{
		$uuid = TestUuid4::generate();

		$this->assertInstanceOf( RepresentsScalarValue::class, $uuid );
		$this->assertInstanceOf( RepresentsUuid4Value::class, $uuid );
		$this->assertInstanceOf( AbstractUuid4Type::class, $uuid );
	}

	/**
	 * @param string $uuidString
	 *
	 * @dataProvider uuidStringProvider
	 */
	public function testCanConstructUuidType( string $uuidString )
	{
		$uuid = new TestUuid4( $uuidString );

		$this->assertInstanceOf( RepresentsScalarValue::class, $uuid );
		$this->assertInstanceOf( RepresentsUuid4Value::class, $uuid );
		$this->assertInstanceOf( AbstractUuid4Type::class, $uuid );
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
				'uuidString' => TestUuid4::generate()->toString(),
			],
		];
	}

	/**
	 * @param RepresentsUuid4Value $UuidTypeA
	 * @param RepresentsUuid4Value $UuidTypeB
	 *
	 * @dataProvider equalUuidsProvider
	 */
	public function testCanCheckForEuqality( RepresentsUuid4Value $UuidTypeA, RepresentsUuid4Value $UuidTypeB )
	{
		$this->assertTrue( $UuidTypeA->equals( $UuidTypeB ) );
		$this->assertTrue( $UuidTypeB->equals( $UuidTypeA ) );
	}

	public function equalUuidsProvider()
	{
		return [
			[
				'UuidTypeA' => new TestUuid4( '12345678-1234-1234-1234-123456789012' ),
				'UuidTypeB' => new TestUuid4( '12345678-1234-1234-1234-123456789012' ),
			],
		];
	}

	/**
	 * @param RepresentsUuid4Value $UuidTypeA
	 * @param RepresentsUuid4Value $UuidTypeB
	 *
	 * @dataProvider notEqualUuidsProvider
	 */
	public function testCanCheckThatUuidTypesAreNotEqual(
		RepresentsUuid4Value $UuidTypeA,
		RepresentsUuid4Value $UuidTypeB
	)
	{
		$this->assertFalse( $UuidTypeA->equals( $UuidTypeB ) );
		$this->assertFalse( $UuidTypeB->equals( $UuidTypeA ) );
	}

	public function notEqualUuidsProvider()
	{
		$uuidStringA      = '12345678-1234-1234-1234-123456789012';
		$uuidStringB      = '12345678-1234-1234-1234-210987654321';
		$extendedUuidType = new class($uuidStringA) extends AbstractUuid4Type
		{
		};

		return [
			# Different values
			[
				'UuidTypeA' => new TestUuid4( $uuidStringA ),
				'UuidTypeB' => new TestUuid4( $uuidStringB ),
			],
			# Different objects
			[
				'UuidTypeA' => new TestUuid4( $uuidStringA ),
				'UuidTypeB' => $extendedUuidType,
			],
		];
	}

	public function testCanRepresentUuidTypeAsString()
	{
		$uuidString = '12345678-1234-1234-1234-123456789012';
		$uuid       = new TestUuid4( $uuidString );
		$this->assertSame( $uuidString, $uuid->toString() );
		$this->assertSame( $uuidString, (string)$uuid );
	}

	/**
	 * @param RepresentsUuid4Value $uuidentifier
	 * @param string               $expectedJson
	 *
	 * @dataProvider uuidToJsonProvider
	 */
	public function testCanSerializeUuidTypeAsJson( RepresentsUuid4Value $uuidentifier, string $expectedJson )
	{
		$this->assertSame( $expectedJson, json_encode( $uuidentifier ) );
	}

	public function uuidToJsonProvider()
	{
		$generatedUuidentifier = TestUuid4::generate();

		return [
			[
				'identifier'   => new TestUuid4( '12345678-1234-1234-1234-123456789012' ),
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
			new TestUuid4( 'foobar' );
		}
		catch ( InvalidUuid4ValueException $ex )
		{
			$this->assertEquals( $ex->getUuid4(), 'foobar' );
			$this->assertEquals( $ex->getMessage(), 'Invalid UUID provided: foobar' );
		}
	}
}
