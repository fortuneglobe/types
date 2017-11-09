<?php declare(strict_types=1);
/**
 * @author h.woltersdorf
 */

namespace Fortuneglobe\Types\Tests\Unit\Fixtures;

use Fortuneglobe\Types\AbstractStringType;

/**
 * Class TestString
 * @package Fortuneglobe\Types\Tests\Unit\Fixtures
 */
final class TestString extends AbstractStringType
{
	protected function guardValueIsValid( string $value )
	{
		// TODO: Implement guardValueIsValid() method.
	}
}
