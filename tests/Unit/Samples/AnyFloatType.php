<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit\Samples;

use Fortuneglobe\Types\AbstractFloatType;

class AnyFloatType extends AbstractFloatType
{
	public function isValid( float $value ): bool
	{
		return true;
	}
}
