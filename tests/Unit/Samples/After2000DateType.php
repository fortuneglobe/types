<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit\Samples;

use Fortuneglobe\Types\AbstractDateType;

class After2000DateType extends AbstractDateType
{
	public function isValid( \DateTimeInterface $value ): bool
	{
		return $value->getTimestamp() - (new \DateTimeImmutable( '2000-01-01 00:00:00' ))->getTimestamp() >= 0;
	}
}
