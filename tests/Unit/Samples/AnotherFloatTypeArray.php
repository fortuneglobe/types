<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit\Samples;

use Fortuneglobe\Types\AbstractFloatTypeArray;
use Fortuneglobe\Types\Interfaces\RepresentsFloatType;

class AnotherFloatTypeArray extends AbstractFloatTypeArray
{
	protected static function isValid( RepresentsFloatType $floatType ): bool
	{
		return true;
	}
}
