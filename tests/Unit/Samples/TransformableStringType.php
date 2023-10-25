<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit\Samples;

use Fortuneglobe\Types\AbstractStringType;

class TransformableStringType extends AbstractStringType
{
	public static function isValid( string $value ): bool
	{
		return true;
	}

	protected function transform( string $value ): string
	{
		return strtolower( $value );
	}
}
