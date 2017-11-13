<?php declare(strict_types=1);

namespace Fortuneglobe\Types\Interfaces;

/**
 * Interface RepresentsUuidValue
 * @package Fortuneglobe\Types\Interfaces
 */
interface RepresentsUuid4Value extends RepresentsStringValue
{
	public static function generate() : RepresentsUuid4Value;
}
