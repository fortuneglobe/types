<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit\Samples;

use Fortuneglobe\Types\AbstractFloatType;
use Fortuneglobe\Types\AbstractFloatTypeArray;
use Fortuneglobe\Types\Interfaces\RepresentsFloatType;

class TransformableFloatTypeArray extends AbstractFloatTypeArray
{
	protected static function isValid( RepresentsFloatType $floatType ): bool
	{
		return true;
	}

	/**
	 * @param RepresentsFloatType[] $floatTypes
	 *
	 * @return RepresentsFloatType[]
	 */
	protected function transform( array $floatTypes ): array
	{
		$transformed = [];
		foreach ( $floatTypes as $floatType )
		{
			if ( $floatType->isPositive() )
			{
				$transformed[] = $floatType;
			}
		}

		return $transformed;
	}

}
