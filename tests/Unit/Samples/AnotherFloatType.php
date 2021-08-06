<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit\Samples;

use Fortuneglobe\Types\AbstractFloatType;

class AnotherFloatType extends AbstractFloatType
{
	public function isValid( float $value ): bool
	{
		return true;
	}
}
