<?php declare(strict_types=1);
/**
 * @author  hollodotme
 * @license MIT (See LICENSE file)
 */

namespace Fortuneglobe\Types\Tests\Unit\Fixtures;

use Fortuneglobe\Types\AbstractFloatType;
use Fortuneglobe\Types\Exceptions\InvalidArgumentException;

/**
 * Class TestPositiveFloatType
 * @package Fortuneglobe\Types\Tests\Unit\Fixtures
 */
final class TestPositiveFloatType extends AbstractFloatType
{
	protected function guardValueIsValid( float $value ) : void
	{
		if ( $value < 0 )
		{
			throw new InvalidArgumentException( 'Float cannot be negative.' );
		}
	}
}
