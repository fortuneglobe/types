# Types
[![CircleCI](https://dl.circleci.com/status-badge/img/gh/fortuneglobe/types/tree/master.svg?style=svg)](https://dl.circleci.com/status-badge/redirect/gh/fortuneglobe/types/tree/master)
![Last Commit](https://badgen.net/github/last-commit/fortuneglobe/types)
![Dependencies](https://badgen.net/github/dependents-repo/fortuneglobe/types)
![Latest release](https://badgen.net/github/release/fortuneglobe/types)
![Code Coverage](https://img.shields.io/static/v1?label=coverage&message=92.53%&color=green)


## Beschreibung

Basistypen, die skalare Typen, aber wie bei DateType auch andere Typen aus der PHP Bibliothek wrappen, um Typen in Anwendungen zu erstellen.

Für jeden Typen (ausgenommen Uuid4) gibt es ein Interface und einen Trait, welcher bereits die meisten Interface-Methoden implementiert.

Die Typen können auch von den abstrakten Klassen abgeleitet werden. Diese implementieren alle Interface-Methoden und stellen zu dem eine automatische Validierung bereit.

Die abstrakten Klassen sind immutable.

Bis auf AbstractDateType haben alle abstrakten Typ Klassen eine `transform` Methode, welche im Konstruktor nach der Validierung (durch `isValid`) aufgerufen wird. 
Diese Methode verändert den Wert standardmäßig nicht. Sie kann vollständig überschrieben werden, falls der Wert verändert werden soll. 

## Anwendungsbeispiele

### Strings

````PHP
class ClientId extends AbstractStringType
{
    public static function isValid( string $value ): bool
    {
        return $value !== '';
    }
}
class ChannelId extends AbstractStringType
{
    public static function isValid( string $value ): bool
    {
        return $value !== '';
    }
}
$clientId           = new ClientId( 'gmo' );
$anotherClientId    = new ClientId( 'gmo' );
$yetAnotherClientId = new ClientId( 'maerz' );
$channelId          = new ChannelId( 'gmo' );
$anotherChannelId   = new ChannelId( 'zalando' );

$clientId->equals( $anotherClientId ) //true
$clientId->equals( $yetAnotherClientId ) //false
$clientId->equals( $channelId ) //false
$clientId->equalsValue( $channelId ) //true
$clientId->equalsValue( 'gmo' ) //true

$newClientId = ClientId::fromStringType( $anotherChannelId );
get_class( $newClientId ); //ClientId
$newClientId->toString(); //zalando
(string)$newClientId; //zalando
````

### Integers

````PHP
class Quantity extends AbstractStringType
{
    public static function isValid( int $value ): bool
    {
        return $value > 0;
    }
}
$quantityOfFirstItem  = new Quantity( 2 );
$quantityOfSecondItem = new Quantity( 5 );

$totalQuantity = $quantityOfFirstItem->add( $quantityOfSecondItem ); //7
$difference    = $quantityOfFirstItem->subtract( $quantityOfSecondItem ); //throws ValidationException
$difference    = $quantityOfSecondItem->subtract( $quantityOfFirstItem ); //3

$incrementedQuantity = $quantityOfFirstItem->increment( $quantityOfSecondItem ); //7
````

Man kann auch mit primitiven Datentypen rechnen.

````PHP
$quantity  = new Quantity( 2 );

$totalQuantity = $quantity->add( 5 ); //7
$difference    = $quantity->subtract( 5 ); //-3

$incrementedQuantity = $quantity->increment( 10 ); //12
````

### Eigene Uuid4-Typen

````PHP
class FulfillmentId extends Uuid4
{
}

$fulfillmentId        = FulfillmentId::generate(); //some UUID4
$anotherFulfillmentId = new FulfillmentId( '9b856c0e-610a-4e38-9ea6-b9ac63cfb521' ); 
````

### Uuid4

````PHP
$uuid4 = (string)Uuid4::generate();
````

### Additional methods provided by trait RepresentingStringType and so also by AbstractStringType

* `getLength`
* `contains`
* `split`
* `splitRaw`
* `matchRegularExpression`

### Additional methods provided by AbstractStringType

* `trim`
* `replace`
* `substring`
* `toLowerCase`
* `toUpperCase`
* `capitalizeFirst`
* `deCapitalizeFirst`
* `toKebabCase`
* `toSnakeCase`
* `toUpperCamelCase`
* `toLowerCamelCase`

### DateType

````PHP
class UpdatedOn extends AbstractDateType
{
    public static function isValid( \DateTimeInterface $value ): bool
    {
        return true;
    }
}

$updatedOn = new UpdatedOn('2023-07-07 08:01:20', new \DateTimeZone( '+0200' ) ))->toString() ); //some UUID4
$updatedOn->hasExpired(); //Checks if current date time is greater than date time of UpdatedOn. Returns boolean
$updatedOn->hasExpired( new \DateInterval('PT15M') ); //Checks if current date time is greater than date time of UpdatedOn and added \DateInterval. Returns boolean
````
### RepresentsDateType extends following interfaces

* `\Stringable`
* `\JsonSerializable`

### Methods provided by RepresentsDateType

* `equals`
* `equalsValue`
* `toDateTime`
* `sub`
* `add`
* `diff`
* `isLessThan`
* `isGreaterThan`
* `isGreaterThanOrEqual`
* `isLessThanOrEqual`
* `hasExpired`
* `format`
* `getOffset`
* `getTimestamp`
* `getTimezone`
* `toString`

## Helpers

`TypesToArrayHelper` kann benutzt werden, um aus Arrays, bestehend aus StringTypes, FloatTypes, IntTypes oder ArrayTypes, Arrays mit primitiven Datentypen zu bauen.

Beispiel:

````PHP
$types = [
    new AnyStringType( 'one' ),
    new AnyStringType( 'two' ),
    new AnotherStringType( 'three' ),
];

TypesToArrayHelper::toStringArray( $types ); // [ 'one', 'two', 'three' ]
````

## Json

Alle Typen implementieren `\JsonSerializable` und können damit mit `json_encode` serialisiert werden.
