<?php declare(strict_types=1);
/**
 * @author h.woltersdorf
 */

namespace Fortuneglobe\Types\Tests\Unit\Fixtures;

use Fortuneglobe\Types\AbstractIntType;

/**
 * Class TestInt
 * @package Fortuneglobe\Types\Tests\Unit\Fixtures
 */
final class TestInt extends AbstractIntType
{
	protected function guardValueIsValid( int $value )
	{
		// TODO: Implement guardValueIsValid() method.
	}
}
