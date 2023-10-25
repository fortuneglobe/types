<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit\Samples;

use Fortuneglobe\Types\AbstractIntTypeArray;
use Fortuneglobe\Types\Interfaces\RepresentsIntType;

class TransformableIntTypeArray extends AbstractIntTypeArray
{
	protected static function isValid( RepresentsIntType $intType ): bool
	{
		return true;
	}

	/**
	 * @param RepresentsIntType[] $intTypes
	 *
	 * @return RepresentsIntType[]
	 */
	protected function transform( array $intTypes ): array
	{
		$transformed = [];
		foreach ( $intTypes as $intType )
		{
			if ( $intType->isPositive() )
			{
				$transformed[] = $intType;
			}
		}

		return $transformed;
	}
}
