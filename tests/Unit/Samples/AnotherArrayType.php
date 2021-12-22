<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit\Samples;

use Fortuneglobe\Types\AbstractArrayType;

class AnotherArrayType extends AbstractArrayType
{
	public static function isValid( array $genericArray ): bool
	{
		return true;
	}
}
