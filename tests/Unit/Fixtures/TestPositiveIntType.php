<?php declare(strict_types=1);
/**
 * @author  hollodotme
 * @license MIT (See LICENSE file)
 */

namespace Fortuneglobe\Types\Tests\Unit\Fixtures;

use Fortuneglobe\Types\AbstractIntType;
use Fortuneglobe\Types\Exceptions\InvalidArgumentException;

/**
 * Class TestPositiveIntType
 * @package Fortuneglobe\Types\Tests\Unit\Fixtures
 */
final class TestPositiveIntType extends AbstractIntType
{
	protected function guardValueIsValid( int $value )
	{
		if ( $value < 0 )
		{
			throw new InvalidArgumentException( 'Int cannot be negative.' );
		}
	}
}
