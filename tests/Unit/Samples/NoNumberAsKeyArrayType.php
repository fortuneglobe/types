<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Tests\Unit\Samples;

use Fortuneglobe\Types\AbstractArrayType;

class NoNumberAsKeyArrayType extends AbstractArrayType
{
	public function isValid( array $genericArray ): bool
	{
		foreach ( $genericArray as $key => $value )
		{
			if ( is_numeric( $key ) )
			{
				return false;
			}
		}

		return true;
	}
}
