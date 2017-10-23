<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit;

use Fortuneglobe\Types\Exceptions\InvalidUUIDValueException;
use Fortuneglobe\Types\Interfaces\RepresentsScalarValue;
use Fortuneglobe\Types\Interfaces\RepresentsUUIDValue;
use Fortuneglobe\Types\UUIDType;
use PHPUnit\Framework\TestCase;

/**
 * Class UUIDTypeTest
 * @package Fortuneglobe\Types\Tests\Unit
 */
final class UUIDTypeTest extends TestCase
{
	public function testCanGenerateUUIDType() : void
	{
		$uuid = UUIDType::generate();

		$this->assertInstanceOf( RepresentsScalarValue::class, $uuid );
		$this->assertInstanceOf( RepresentsUUIDValue::class, $uuid );
		$this->assertInstanceOf( UUIDType::class, $uuid );
	}

	/**
	 * @param string $uuidString
	 *
	 * @dataProvider uuidStringProvider
	 */
	public function testCanConstructUUIDType( string $uuidString )
	{
		$uuid = new UUIDType( $uuidString );

		$this->assertInstanceOf( RepresentsScalarValue::class, $uuid );
		$this->assertInstanceOf( RepresentsUUIDValue::class, $uuid );
		$this->assertInstanceOf( UUIDType::class, $uuid );
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
				'uuidString' => UUIDType::generate()->toString(),
			],
		];
	}

	/**
	 * @param UUIDType $UUIDTypeA
	 * @param UUIDType $UUIDTypeB
	 *
	 * @dataProvider equalUUIDsProvider
	 */
	public function testCanCheckForEuqality( UUIDType $UUIDTypeA, UUIDType $UUIDTypeB )
	{
		$this->assertTrue( $UUIDTypeA->equals( $UUIDTypeB ) );
		$this->assertTrue( $UUIDTypeB->equals( $UUIDTypeA ) );
	}

	public function equalUUIDsProvider()
	{
		return [
			[
				'UUIDTypeA' => new UUIDType( '12345678-1234-1234-1234-123456789012' ),
				'UUIDTypeB' => new UUIDType( '12345678-1234-1234-1234-123456789012' ),
			],
		];
	}

	/**
	 * @param UUIDType $UUIDTypeA
	 * @param UUIDType $UUIDTypeB
	 *
	 * @dataProvider notEqualUUIDsProvider
	 */
	public function testCanCheckThatUUIDTypesAreNotEqual( UUIDType $UUIDTypeA, UUIDType $UUIDTypeB )
	{
		$this->assertFalse( $UUIDTypeA->equals( $UUIDTypeB ) );
		$this->assertFalse( $UUIDTypeB->equals( $UUIDTypeA ) );
	}

	public function notEqualUUIDsProvider()
	{
		$uuidStringA      = '12345678-1234-1234-1234-123456789012';
		$uuidStringB      = '12345678-1234-1234-1234-210987654321';
		$extendedUUIDType = new class($uuidStringA) extends UUIDType
		{
		};

		return [
			# Different values
			[
				'UUIDTypeA' => new UUIDType( $uuidStringA ),
				'UUIDTypeB' => new UUIDType( $uuidStringB ),
			],
			# Different objects
			[
				'UUIDTypeA' => new UUIDType( $uuidStringA ),
				'UUIDTypeB' => $extendedUUIDType,
			],
		];
	}

	public function testCanRepresentUUIDTypeAsString()
	{
		$uuidString = '12345678-1234-1234-1234-123456789012';
		$uuid       = new UUIDType( $uuidString );
		$this->assertSame( $uuidString, $uuid->toString() );
		$this->assertSame( $uuidString, (string)$uuid );
	}

	/**
	 * @param UUIDType $uuidentifier
	 * @param string   $expectedJson
	 *
	 * @dataProvider uuidToJsonProvider
	 */
	public function testCanSerializeUUIDTypeAsJson( UUIDType $uuidentifier, string $expectedJson )
	{
		$this->assertSame( $expectedJson, json_encode( $uuidentifier ) );
	}

	public function uuidToJsonProvider()
	{
		$generatedUuidentifier = UUIDType::generate();

		return [
			[
				'identifier'             => new UUIDType( '12345678-1234-1234-1234-123456789012' ),
				'expectedJsonSerialized' => '"12345678-1234-1234-1234-123456789012"',
			],
			[
				'identifier'             => $generatedUuidentifier,
				'expectedJsonSerialized' => sprintf( '"%s"', $generatedUuidentifier->toString() ),
			],
		];
	}

	public function testExpectExceptionIfInvalidUUIDStringIsProvided()
	{
		try
		{
			new UUIDType( 'foobar' );
		}
		catch ( InvalidUUIDValueException $ex )
		{
			$this->assertEquals( $ex->getUUID(), 'foobar' );
			$this->assertEquals( $ex->getMessage(), 'Invalid UUID provided: foobar' );
		}
	}
}
