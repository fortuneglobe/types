<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit;

use Fortuneglobe\Types\Exceptions\InvalidUuidValueException;
use Fortuneglobe\Types\Interfaces\RepresentsScalarValue;
use Fortuneglobe\Types\Interfaces\RepresentsUuidValue;
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
		$uuid = UuidType::generate();

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
		$uuid = new UuidType( $uuidString );

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
				'uuidString' => UuidType::generate()->toString(),
			],
		];
	}

	/**
	 * @param UuidType $UuidTypeA
	 * @param UuidType $UuidTypeB
	 *
	 * @dataProvider equalUuidsProvider
	 */
	public function testCanCheckForEuqality( UuidType $UuidTypeA, UuidType $UuidTypeB )
	{
		$this->assertTrue( $UuidTypeA->equals( $UuidTypeB ) );
		$this->assertTrue( $UuidTypeB->equals( $UuidTypeA ) );
	}

	public function equalUuidsProvider()
	{
		return [
			[
				'UuidTypeA' => new UuidType( '12345678-1234-1234-1234-123456789012' ),
				'UuidTypeB' => new UuidType( '12345678-1234-1234-1234-123456789012' ),
			],
		];
	}

	/**
	 * @param UuidType $UuidTypeA
	 * @param UuidType $UuidTypeB
	 *
	 * @dataProvider notEqualUuidsProvider
	 */
	public function testCanCheckThatUuidTypesAreNotEqual( UuidType $UuidTypeA, UuidType $UuidTypeB )
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
				'UuidTypeA' => new UuidType( $uuidStringA ),
				'UuidTypeB' => new UuidType( $uuidStringB ),
			],
			# Different objects
			[
				'UuidTypeA' => new UuidType( $uuidStringA ),
				'UuidTypeB' => $extendedUuidType,
			],
		];
	}

	public function testCanRepresentUuidTypeAsString()
	{
		$uuidString = '12345678-1234-1234-1234-123456789012';
		$uuid       = new UuidType( $uuidString );
		$this->assertSame( $uuidString, $uuid->toString() );
		$this->assertSame( $uuidString, (string)$uuid );
	}

	/**
	 * @param UuidType $uuidentifier
	 * @param string   $expectedJson
	 *
	 * @dataProvider uuidToJsonProvider
	 */
	public function testCanSerializeUuidTypeAsJson( UuidType $uuidentifier, string $expectedJson )
	{
		$this->assertSame( $expectedJson, json_encode( $uuidentifier ) );
	}

	public function uuidToJsonProvider()
	{
		$generatedUuidentifier = UuidType::generate();

		return [
			[
				'identifier'             => new UuidType( '12345678-1234-1234-1234-123456789012' ),
				'expectedJsonSerialized' => '"12345678-1234-1234-1234-123456789012"',
			],
			[
				'identifier'             => $generatedUuidentifier,
				'expectedJsonSerialized' => sprintf( '"%s"', $generatedUuidentifier->toString() ),
			],
		];
	}

	public function testExpectExceptionIfInvalidUuidStringIsProvided()
	{
		try
		{
			new UuidType( 'foobar' );
		}
		catch ( InvalidUuidValueException $ex )
		{
			$this->assertEquals( $ex->getUuid(), 'foobar' );
			$this->assertEquals( $ex->getMessage(), 'Invalid UUID provided: foobar' );
		}
	}
}
