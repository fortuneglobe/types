<?php declare(strict_types=1);

namespace Fortuneglobe\Types;

use Fortuneglobe\Types\Interfaces\RepresentsIntType;

class IntTypeArray extends AbstractIntTypeArray
{
	protected static function isValid( RepresentsIntType $intType ): bool
	{
		return true;
	}
}
