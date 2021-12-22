<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit\Samples;

use Fortuneglobe\Types\AbstractIntType;

class JustAnIntType extends AbstractIntType
{
	public static function isValid( int $value ): bool
	{
		return true;
	}
}
