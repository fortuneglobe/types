<?php declare(strict_types=1);

namespace Fortuneglobe\Types;

use Fortuneglobe\Types\Interfaces\RepresentsStringType;

class StringTypeArray extends AbstractStringTypeArray
{
	protected static function isValid( RepresentsStringType $stringType ): bool
	{
		return true;
	}
}
