<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Helpers;

use Fortuneglobe\Types\Interfaces\RepresentsArrayType;
use Fortuneglobe\Types\Interfaces\RepresentsFloatType;
use Fortuneglobe\Types\Interfaces\RepresentsIntType;
use Fortuneglobe\Types\Interfaces\RepresentsStringType;

class TypesToArrayHelper
{
	/**
	 * @param RepresentsStringType[]|RepresentsIntType[]|RepresentsFloatType[]|\Stringable[] $types
	 *
	 * @return string[]
	 */
	public static function toStringArray( array $types ): array
	{
		$rawTypes = [];
		foreach ( $types as $type )
		{
			$rawTypes[] = (string)$type;
		}

		return $rawTypes;
	}

	/**
	 * @param RepresentsIntType[] $types
	 *
	 * @return int[]
	 */
	public static function toIntArray( array $types ): array
	{
		$rawTypes = [];
		foreach ( $types as $type )
		{
			$rawTypes[] = $type->toInt();
		}

		return $rawTypes;
	}

	/**
	 * @param RepresentsFloatType[]|RepresentsIntType[] $types
	 *
	 * @return float[]
	 */
	public static function toFloatArray( array $types ): array
	{
		$rawTypes = [];
		foreach ( $types as $type )
		{
			$rawTypes[] = $type->toFloat();
		}

		return $rawTypes;
	}

	/**
	 * @param RepresentsArrayType[] $types
	 *
	 * @return array[]
	 */
	public static function toArrayArray( array $types ): array
	{
		$rawTypes = [];
		foreach ( $types as $type )
		{
			$rawTypes[] = $type->toArray();
		}

		return $rawTypes;
	}
}
