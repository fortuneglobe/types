<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit\Samples;

use Fortuneglobe\Types\AbstractDateType;

class AnyDateType extends AbstractDateType
{
	public function isValid( \DateTimeInterface $value ): bool
	{
		return true;
	}
}
