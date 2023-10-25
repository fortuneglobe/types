<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit\Samples;

use Fortuneglobe\Types\AbstractStringTypeArray;
use Fortuneglobe\Types\Interfaces\RepresentsStringType;

class TransformableStringTypeArray extends AbstractStringTypeArray
{
	protected static function isValid( RepresentsStringType $stringType ): bool
	{
		return true;
	}

	/**
	 * @param RepresentsStringType[] $stringTypes
	 *
	 * @return RepresentsStringType[]
	 */
	protected function transform( array $stringTypes ): array
	{
		$transformed = [];
		foreach ( $stringTypes as $stringType )
		{
			if ( $stringType->toString() === strtolower( (string)$stringType ) )
			{
				$transformed[] = $stringType;
			}
		}

		return $transformed;
	}
}
