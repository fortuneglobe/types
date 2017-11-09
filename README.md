# fortuneglobe/types

## Description

Basic type classes wrapping scalar values to create types in applications.
These classes are declared `abstract` and must be extended by appropriate named type classes in your application.

## Requirements

* PHP >= 7.1

## Installation

```bash
composer require fortuneglobe/types
```

## Usage

### Available base types

* AbstractStringType
* AbstractFloatType
* AbstractIntType
* AbstractUuidType (extends AbstractStringType)

### Possible exceptions

```
\Fortuneglobe\Types\Exceptions\InvalidArgumentException
                               |- InvalidFloatValueException
                               |- InvalidIntValueException
                               `- InvalidUuid4ValueException
```
  

### AbstractStringType

```php
<?php declare(strict_types=1);

namespace YourVendor\YourProject;

use Fortuneglobe\Types\AbstractStringType;
use Fortuneglobe\Types\Exceptions\InvalidArgumentException;

final class UserId extends AbstractStringType 
{
    protected function guardValueIsValid( string $value ) 
    {
        if (preg_match('#\s#', $value))
        {
            throw new InvalidArgumentException('User IDs cannot contain whitespace characters.');   
        }
    }
}

$userId = new UserId('fortuneglobe-user-001');

echo $userId                . PHP_EOL;
echo (string)$userId        . PHP_EOL;
echo $userId->toString()    . PHP_EOL;
echo json_encode($userId);
```

**Prints:**

```
fortuneglobe-user-001
fortuneglobe-user-001
fortuneglobe-user-001
"fortuneglobe-user-001"
```

### AbstractFloatType

```php
<?php declare(strict_types=1);

namespace YourVendor\YourProject;

use Fortuneglobe\Types\AbstractFloatType;
use Fortuneglobe\Types\Exceptions\InvalidArgumentException;

final class DegreeCelsius extends AbstractFloatType 
{
    protected function guardValueIsValid( float $value ) 
    {
        if (-273.15 > $value)
        {
            throw new InvalidArgumentException('Degree Celsius cannot be below absolute zero.');
        }
    }	
}

$temperature = new DegreeCelsuis(22.5);

echo $temperature                . PHP_EOL;
echo (string)$temperature        . PHP_EOL;
echo $temperature->toString()    . PHP_EOL;
echo json_encode($temperature);
```

**Prints:**

```
22.5
22.5
22.5
22.5
```

#### Reconstruct from string value
    
```php
$temperature = DegreeCelsuis::fromString('22.5');
``` 

This call throws an `Fortuneglobe\Types\Exceptions\InvalidFloatValueException` if the provided string is not a float number.

### AbstractIntType

```php
<?php declare(strict_types=1);

namespace YourVendor\YourProject;

use Fortuneglobe\Types\AbstractIntType;
use Fortuneglobe\Types\Exceptions\InvalidArgumentException;

final class Version extends AbstractIntType 
{
    protected function guardValueIsValid( int $value ) 
    {
        if (0 > $value)
        {
            throw new InvalidArgumentException('Version number cannot be negative.');    
        }
    }	
}

$version = new Version(42);

echo $version                . PHP_EOL;
echo (string)$version        . PHP_EOL;
echo $version->toString()    . PHP_EOL;
echo json_encode($version);
```

**Prints:**

```
42
42
42
42
```

#### Reconstruct from string value
    
```php
$version = Version::fromString('42');
``` 

This call throws an `Fortuneglobe\Types\Exceptions\InvalidIntValueException` if the provided string is not an integer number.


### AbstractUuid4Type

This basic type only handles UUID v4 strings.

```php
<?php declare(strict_types=1);

namespace YourVendor\YourProject;

use Fortuneglobe\Types\AbstractUuid4Type;

final class OrderId extends AbstractUuid4Type {}

$orderId = OrderId::generate();

echo $orderId                . PHP_EOL;
echo (string)$orderId        . PHP_EOL;
echo $orderId->toString()    . PHP_EOL;
echo json_encode($orderId);
```

**Prints (for example):**

```
7b13bcba-a18f-4887-b784-9417262862e4
7b13bcba-a18f-4887-b784-9417262862e4
7b13bcba-a18f-4887-b784-9417262862e4
"7b13bcba-a18f-4887-b784-9417262862e4"
```

#### Reconstruct from string value

```php
$orderId = new OrderId('7b13bcba-a18f-4887-b784-9417262862e4');
``` 

This call throws an `Fortuneglobe\Types\Exceptions\InvalidUuidValueException` if the provided string is not a valid UUID.
