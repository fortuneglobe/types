<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit\Samples;

use Fortuneglobe\Types\AbstractDateType;

class AnotherDateType extends AbstractDateType
{
	public function isValid( \DateTimeInterface $value ): bool
	{
		return true;
	}
}
