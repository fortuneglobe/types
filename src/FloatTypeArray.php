<?php declare(strict_types=1);

namespace Fortuneglobe\Types;

use Fortuneglobe\Types\Interfaces\RepresentsFloatType;

class FloatTypeArray extends AbstractFloatTypeArray
{
	public static function isValid( RepresentsFloatType $floatType ): bool
	{
		return true;
	}
}
