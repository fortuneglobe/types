<?php declare(strict_types=1);

namespace Fortuneglobe\Types;

use Fortuneglobe\Types\Interfaces\RepresentsStringType;
use Fortuneglobe\Types\Traits\RepresentingUuid4;

class Uuid4 implements RepresentsStringType
{
	use RepresentingUuid4;

	public function equals( RepresentsStringType $type ): bool
	{
		return get_class( $this ) === get_class( $type ) && $this->equalsValue( $type );
	}

	public function equalsValue( RepresentsStringType|string $value ): bool
	{
		return $this->toString() === (is_string( $value ) ? $value : $value->toString());
	}
}
