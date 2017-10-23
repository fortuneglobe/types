# fortuneglobe/types

## Description

Basic type classes wrapping scalar values to create types in applications.
These classes are meant to be extended by appropriate named type classes.

## Requirements

* PHP >= 7.1

## Installation

```bash
composer require fortuneglobe/types
```

## Usage

### Available base types

* StringType
* FloatType
* IntType
* UUIDType (extends StringType)

### Possible exceptions

```
\Fortuneglobe\Types\Exceptions\InvalidArgumentException
                               |- InvalidFloatValueException
                               |- InvalidIntValueException
                               |- InvalidStringValueException
                               `- InvalidFloatValueException
```
  

### StringType

```php
<?php declare(strict_types=1);

namespace YourVendor\YourProject;

use Fortuneglobe\Types\StringType;

final class UserId extends StringType {}

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

### FloatType

```php
<?php declare(strict_types=1);

namespace YourVendor\YourProject;

use Fortuneglobe\Types\FloatType;

final class DegreeCelsuis extends FloatType {}

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
"22.5"
```

#### Reconstruct from string value
    
```php
$temperature = DegreeCelsuis::fromString('22.5');
``` 

This call throws an `Fortuneglobe\Types\Exceptions\InvalidFloatValueException` if the provided string is not a float number.

### IntType

```php
<?php declare(strict_types=1);

namespace YourVendor\YourProject;

use Fortuneglobe\Types\IntType;

final class Version extends IntType {}

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
"42"
```

#### Reconstruct from string value
    
```php
$version = Version::fromString('42');
``` 

This call throws an `Fortuneglobe\Types\Exceptions\InvalidIntValueException` if the provided string is not an integer number.


### UUIDType

```php
<?php declare(strict_types=1);

namespace YourVendor\YourProject;

use Fortuneglobe\Types\UUIDType;

final class OrderId extends UUIDType {}

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

This call throws an `Fortuneglobe\Types\Exceptions\InvalidUUIDValueException` if the provided string is not a valid UUID.
