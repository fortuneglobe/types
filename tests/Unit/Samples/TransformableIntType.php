<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit\Samples;

use Fortuneglobe\Types\AbstractIntType;

class TransformableIntType extends AbstractIntType
{
	public static function isValid( int $value ): bool
	{
		return true;
	}

	protected function transform( int $value ): int
	{
		return $value + 1;
	}
}
