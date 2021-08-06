<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit\Samples;

use Fortuneglobe\Types\AbstractStringType;

class AnotherStringType extends AbstractStringType
{
	public function isValid( string $value ): bool
	{
		return true;
	}
}
