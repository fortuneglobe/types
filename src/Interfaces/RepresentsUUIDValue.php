<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Interfaces;

/**
 * Interface RepresentsUUIDValue
 * @package Fortuneglobe\Types\Interfaces
 */
interface RepresentsUUIDValue extends RepresentsStringValue
{
	public static function generate() : RepresentsUUIDValue;
}
