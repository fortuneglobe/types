<?php declare(strict_types=1);
/**
 * @author  hollodotme
 * @license MIT (See LICENSE file)
 */

namespace Fortuneglobe\Types\Tests\Unit\Fixtures;

use Fortuneglobe\Types\AbstractStringType;
use Fortuneglobe\Types\Exceptions\InvalidArgumentException;

/**
 * Class TestNonEmptyStringType
 * @package Fortuneglobe\Types\Tests\Unit\Fixtures
 */
final class TestNonEmptyStringType extends AbstractStringType
{
	protected function guardValueIsValid( string $value )
	{
		if ( empty( $value ) )
		{
			throw new InvalidArgumentException( 'String cannot be empty.' );
		}
	}
}
