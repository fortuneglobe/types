<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Interfaces;

interface RepresentsStringType extends \Stringable, \JsonSerializable
{
	public function equals( RepresentsStringType $type ): bool;

	public function equalsValue( RepresentsStringType|string $value ): bool;

	public function toString(): string;

	public function trim( string $characters = " \n\r\t\v\x00" ): RepresentsStringType;

	public function replace( array|string|RepresentsStringType|\Stringable $search, array|string|RepresentsStringType|\Stringable $replace ): RepresentsStringType;

	public function substring( int $offset, ?int $length = null ): RepresentsStringType;

	public function toLowerCase(): RepresentsStringType;

	public function toUpperCase(): RepresentsStringType;

	public function capitalizeFirst(): RepresentsStringType;

	public function deCapitalizeFirst(): RepresentsStringType;

	public function toKebabCase(): RepresentsStringType;

	public function toSnakeCase(): RepresentsStringType;

	public function toUpperCamelCase(): RepresentsStringType;

	public function toLowerCamelCase(): RepresentsStringType;

	/**
	 * @param string $delimiter
	 *
	 * @return \Iterator|static[]
	 */
	public function split( string $delimiter ): \Iterator;

	/**
	 * @param string $delimiter
	 *
	 * @return string[]
	 */
	public function splitRaw( string $delimiter ): array;

	public function matchRegularExpression( string|RepresentsStringType|\Stringable $pattern, &$matches, int $flags, int $offset ): bool;

	public function getLength(): int;

	public function isEmpty(): bool;

	public function contains( string|RepresentsStringType|\Stringable $needle ): bool;

	public function containsOneOf( string|RepresentsStringType|\Stringable...$values ): bool;

	public function isOneOf( string|RepresentsStringType|\Stringable...$values ): bool;
}
