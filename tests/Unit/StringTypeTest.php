<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit;

use Fortuneglobe\Types\AbstractStringType;
use Fortuneglobe\Types\Exceptions\ValidationException;
use Fortuneglobe\Types\Interfaces\RepresentsStringType;
use Fortuneglobe\Types\Tests\Unit\Samples\AnotherStringType;
use Fortuneglobe\Types\Tests\Unit\Samples\AnyStringType;
use Fortuneglobe\Types\Tests\Unit\Samples\NoQuestionMarkStringType;
use Fortuneglobe\Types\Tests\Unit\Samples\RandomStringableType;
use Fortuneglobe\Types\Traits\RepresentingStringType;
use PHPUnit\Framework\TestCase;

class StringTypeTest extends TestCase
{
	public function ToStringDataProvider(): array
	{
		return [
			[ new AnyStringType( 'test' ), 'test' ],
			[ new AnyStringType( '??!!' ), '??!!' ],
			[ new AnyStringType( 'Just a small phrase' ), 'Just a small phrase' ],
			[ new AnyStringType( '' ), '' ],
			[ new AnyStringType( ' ' ), ' ' ],
		];
	}

	/**
	 * @dataProvider ToStringDataProvider
	 *
	 * @param RepresentsStringType $stringType
	 * @param string               $expectedString
	 */
	public function testToString( RepresentsStringType $stringType, string $expectedString ): void
	{
		self::assertSame( $expectedString, $stringType->toString() );
		self::assertSame( $expectedString, (string)$stringType );
	}

	public function testInvalidValueThrowsException(): void
	{
		$this->expectException( ValidationException::class );
		$this->expectExceptionMessage( 'Invalid NoQuestionMarkStringType: ??' );

		new NoQuestionMarkStringType( '??' );
	}

	public function testFromStringType(): void
	{
		self::assertEquals( new AnyStringType( 'Test' ), AnyStringType::fromStringType( new AnotherStringType( 'Test' ) ) );
	}

	public function testIfSameClassWithSameValueEquals()
	{
		self::assertTrue( (new AnyStringType( 'Test' ))->equals( new AnyStringType( 'Test' ) ) );
	}

	public function testIfSameClassWithAnotherValueIsNotEqual()
	{
		self::assertFalse( (new AnyStringType( 'Test' ))->equals( new AnyStringType( 'test' ) ) );
	}

	public function testIfAnotherClassWithSameValueIsNotEqual()
	{
		self::assertFalse( (new AnyStringType( 'Test' ))->equals( new AnotherStringType( 'Test' ) ) );
	}

	public function testIfInvalidValueThrowsException(): void
	{
		$this->expectException( ValidationException::class );

		new class('Test') extends AbstractStringType {
			public static function isValid( string $value ): bool
			{
				return false;
			}
		};
	}

	public function testIfValidValueDoesNotThrowException(): void
	{
		$this->expectNotToPerformAssertions();

		new class('Test') extends AbstractStringType {
			public static function isValid( string $value ): bool
			{
				return true;
			}
		};
	}

	public function EqualsValueDataProvider(): array
	{
		return [
			[
				new AnyStringType( 'Test 1' ),
				new AnotherStringType( 'Test 1' ),
				true,
			],
			[
				new AnyStringType( 'Test 1' ),
				new AnyStringType( 'Test 1' ),
				true,
			],
			[
				new AnyStringType( 'Test 1' ),
				new AnotherStringType( 'Test 2' ),
				false,
			],
			[
				new AnyStringType( 'Test 1' ),
				new AnyStringType( 'Test 2' ),
				false,
			],
		];
	}

	/**
	 * @dataProvider EqualsValueDataProvider
	 *
	 * @param RepresentsStringType $stringType
	 * @param RepresentsStringType $anotherStringType
	 * @param bool                 $expectedResult
	 */
	public function testIfEqualsValueComparesOnlyValues( RepresentsStringType $stringType, RepresentsStringType $anotherStringType, bool $expectedResult ): void
	{
		self::assertSame( $expectedResult, $stringType->equalsValue( $anotherStringType ) );
		self::assertSame( $expectedResult, $stringType->equalsValue( $anotherStringType->toString() ) );
	}

	public function testEqualsValueWithStringables(): void
	{
		self::assertTrue( (new AnyStringType( 'Test 1' ))->equalsValue( new RandomStringableType( 'Test 1' ) ) );
		self::assertFalse( (new AnyStringType( 'Test 1' ))->equalsValue( new RandomStringableType( 'Test 2' ) ) );
	}

	public function testIsEmpty(): void
	{
		self::assertTrue( (new AnyStringType( '' ))->isEmpty() );
		self::assertFalse( (new AnyStringType( '0' ))->isEmpty() );
		self::assertFalse( (new AnyStringType( ' ' ))->isEmpty() );
	}

	public function testInitializeClassUsingTrait(): void
	{
		$stringType = new class('a') {
			use RepresentingStringType;
		};

		self::assertEquals( 'a', $stringType->toString() );
	}

	public function testGetLength(): void
	{
		self::assertEquals( 18, (new AnyStringType( 'Count this please!' ))->getLength() );
	}

	public function TrimDataProvider(): array
	{
		return [
			[ ' Trim this! ', 'Trim this!' ],
			[ '  Trim this!  ', 'Trim this!' ],
			[ "   Trim this!  \n", 'Trim this!' ],
		];
	}

	/**
	 * @dataProvider TrimDataProvider
	 *
	 * @param string $anyString
	 * @param string $expectedString
	 *
	 * @return void
	 */
	public function testTrim( string $anyString, string $expectedString ): void
	{
		self::assertEquals( $expectedString, (new AnyStringType( $anyString ))->trim() );
	}

	public function testContains(): void
	{
		self::assertTrue( (new AnyStringType( 'This is a text containing dogs and not only cats.' ))->contains( 'dogs' ) );
		self::assertTrue( (new AnyStringType( 'This is a text containing dogs and not only cats.' ))->contains( new AnyStringType( 'dogs' ) ) );
		self::assertFalse( (new AnyStringType( 'This is a text containing dogs and not only cats.' ))->contains( 'cow' ) );
	}

	public function testReplace(): void
	{
		self::assertEquals( new AnyStringType( 'I don\'t like red' ), (new AnyStringType( 'I don\'t like yellow' ))->replace( 'yellow', 'red' ) );
		self::assertEquals(
			new AnyStringType( 'I don\'t like red' ),
			(new AnyStringType( 'I don\'t like yellow' ))->replace( new AnyStringType( 'yellow' ), new AnyStringType( 'red' ) )
		);
		self::assertEquals(
			new AnyStringType( 'I don\'t like red' ),
			(new AnyStringType( 'I don\'t like yellow' ))->replace( new RandomStringableType( 'yellow' ), new RandomStringableType( 'red' ) )
		);
		self::assertEquals( new AnyStringType( 'I like red' ), (new AnyStringType( 'I don\'t like yellow' ))->replace( [ 'yellow', 'don\'t ' ], [ 'red', '' ] ) );

		$anyStringType = new AnyStringType( 'I don\'t like blue' );
		self::assertNotSame( $anyStringType, $anyStringType->replace( 'yellow', 'red' ) );
	}

	public function SubstringDataProvider(): array
	{
		return [
			[ 'abcdef', -1, null, 'f' ],
			[ 'abcdef', -2, null, 'ef' ],
			[ 'abcdef', -3, 1, 'd' ],
			[ 'abcdef', 0, -1, 'abcde' ],
			[ 'abcdef', 2, -1, 'cde' ],
			[ 'abcdef', 4, -4, '' ],
			[ 'abcdef', -3, -1, 'de' ],
		];
	}

	/**
	 * @dataProvider SubstringDataProvider
	 *
	 * @param string   $anyString
	 * @param int      $offset
	 * @param int|null $length
	 * @param string   $expectedString
	 *
	 * @return void
	 */
	public function testSubstring( string $anyString, int $offset, ?int $length, string $expectedString ): void
	{
		self::assertEquals( new AnyStringType( $expectedString ), (new AnyStringType( $anyString ))->substring( $offset, $length ) );
	}

	public function testSplit(): void
	{
		self::assertEquals(
			[
				new AnyStringType( 'cat' ),
				new AnyStringType( 'dog' ),
				new AnyStringType( 'cow' ),
			],
			iterator_to_array(
				(new AnyStringType( 'cat,dog,cow' ))->split( ',' ),
				false
			)
		);
	}

	public function testSplitRaw(): void
	{
		self::assertEquals( [ 'cat', 'dog', 'cow' ], (new AnyStringType( 'cat,dog,cow' ))->splitRaw( ',' ) );
	}

	public function testToLowerCase(): void
	{
		self::assertEquals( new AnyStringType( 'i like only lower case' ), (new AnyStringType( 'I like only LOWER CASE' ))->toLowerCase() );
	}

	public function testToUpperCase(): void
	{
		self::assertEquals( new AnyStringType( 'I LIKE ONLY UPPER CASE' ), (new AnyStringType( 'I like only upper case' ))->toUpperCase() );
	}

	public function testCapitalizeFirst(): void
	{
		self::assertEquals( new AnyStringType( 'My id' ), (new AnyStringType( 'my id' ))->capitalizeFirst() );
	}

	public function testDeCapitalizeFirst(): void
	{
		self::assertEquals( new AnyStringType( 'myId Ok!' ), (new AnyStringType( 'MyId Ok!' ))->deCapitalizeFirst() );
	}

	public function RegularExpressionDataProvider(): array
	{
		return [
			[ 'This is a small text', '/small/', true ],
			[ 'This is a small text', '/not-existing/', false ],
			[ '/abc/url', '/^\/(?<id>.*\/url$)/', true ],
		];
	}

	/**
	 * @dataProvider RegularExpressionDataProvider
	 *
	 * @param string $anyString
	 * @param string $pattern
	 * @param bool   $expectedResult
	 *
	 * @return void
	 */
	public function testMatchRegularExpression( string $anyString, string $pattern, bool $expectedResult )
	{
		@preg_match( $pattern, $anyString, $expectedMatches );

		self::assertEquals( $expectedResult, (new AnyStringType( $anyString ))->matchRegularExpression( $pattern, $matches ) );
		self::assertEquals( $expectedResult, (new AnyStringType( $anyString ))->matchRegularExpression( new AnyStringType( $pattern ), $matches ) );
		self::assertEquals( $expectedMatches, $matches );
	}

	public function testIfMatchRegularExpressionThrowsExceptionOnInvalidPattern(): void
	{
		$this->expectException( \LogicException::class );

		(new AnyStringType( 'Abc' ))->matchRegularExpression( '}{' );
	}

	public function KebabCaseDataProvider(): array
	{
		return [
			[ 'nice-kebab-case', 'nice-kebab-case' ],
			[ 'Nice Kebab Case', 'Nice-Kebab-Case' ],
			[ 'nice kebab Case', 'nice-kebab-Case' ],
			[ 'nice  kebab  case', 'nice-kebab-case' ],
			[ 'nice  kebab  Case', 'nice-kebab-Case' ],
			[ 'nice-kebab-Case', 'nice-kebab-Case' ],
			[ 'nice_kebab_Case', 'nice-kebab-Case' ],
			[ 'nice_-_-kebab_-_-Case', 'nice-kebab-Case' ],
		];
	}

	/**
	 * @dataProvider KebabCaseDataProvider
	 *
	 * @param string $anyString
	 * @param string $expectedString
	 *
	 * @return void
	 */
	public function testToKebabCase( string $anyString, string $expectedString ): void
	{
		self::assertEquals( $expectedString, (new AnyStringType( $anyString ))->toKebabCase()->toString() );
	}

	public function SnakeCaseDataProvider(): array
	{
		return [
			[ 'nice_snake_case', 'nice_snake_case' ],
			[ 'Nice Snake Case', 'Nice_Snake_Case' ],
			[ 'nice snake Case', 'nice_snake_Case' ],
			[ 'nice  snake  Case', 'nice_snake_Case' ],
			[ 'nice  snake  Case', 'nice_snake_Case' ],
			[ 'nice-snake-Case', 'nice_snake_Case' ],
			[ 'nice_snake_Case', 'nice_snake_Case' ],
			[ 'nice_-_-snake_-_-Case', 'nice_snake_Case' ],
		];
	}

	/**
	 * @dataProvider SnakeCaseDataProvider
	 *
	 * @param string $anyString
	 * @param string $expectedString
	 *
	 * @return void
	 */
	public function testToSnakeCase( string $anyString, string $expectedString ): void
	{
		self::assertEquals( $expectedString, (new AnyStringType( $anyString ))->toSnakeCase()->toString() );
	}

	public function LowerCamelCaseDataProvider(): array
	{
		return [
			[ 'lowerCamelCase', 'lowerCamelCase' ],
			[ 'Lower Camel Case', 'lowerCamelCase' ],
			[ 'lower camel case', 'lowerCamelCase' ],
			[ 'lower  camel  case', 'lowerCamelCase' ],
			[ 'lower  camel  case', 'lowerCamelCase' ],
			[ 'lower-camel-case', 'lowerCamelCase' ],
			[ 'lower_camel_case', 'lowerCamelCase' ],
			[ 'lower_-_-camel_-_-case', 'lowerCamelCase' ],
		];
	}

	/**
	 * @dataProvider LowerCamelCaseDataProvider
	 *
	 * @param string $anyString
	 * @param string $expectedString
	 *
	 * @return void
	 */
	public function testToLowerCamelCase( string $anyString, string $expectedString ): void
	{
		self::assertEquals( $expectedString, (new AnyStringType( $anyString ))->toLowerCamelCase()->toString() );
	}

	public function UpperCamelCaseDataProvider(): array
	{
		return [
			[ 'UpperCamelCase', 'UpperCamelCase' ],
			[ 'Upper Camel Case', 'UpperCamelCase' ],
			[ 'upper camel case', 'UpperCamelCase' ],
			[ 'upper  camel  case', 'UpperCamelCase' ],
			[ 'upper  camel  case', 'UpperCamelCase' ],
			[ 'upper-camel-case', 'UpperCamelCase' ],
			[ 'upper_camel_case', 'UpperCamelCase' ],
			[ 'upper_-_-camel_-_-case', 'UpperCamelCase' ],
		];
	}

	/**
	 * @dataProvider UpperCamelCaseDataProvider
	 *
	 * @param string $anyString
	 * @param string $expectedString
	 *
	 * @return void
	 */
	public function testToUpperCamelCase( string $anyString, string $expectedString ): void
	{
		self::assertEquals( $expectedString, (new AnyStringType( $anyString ))->toUpperCamelCase()->toString() );
	}
}
