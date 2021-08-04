<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit\Samples;

use Fortuneglobe\Types\AbstractIntType;

class AnotherIntType extends AbstractIntType
{
	public function isValid( int $value ): bool
	{
		return true;
	}
}
