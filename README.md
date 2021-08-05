# Types

## Beschreibung

Basistypen, die skalare Typen, aber wie bei DateType auch andere Typen aus der PHP Bibliothek wrappen, um Typen in Anwendungen zu erstellen.

Für jeden Typen (ausgenommen Uuid4) gibt es ein Interface und einen Trait, welcher bereits die meisten Interface-Methoden implementiert.

Die Typen können auch von den abstrakten Klassen abgeleitet werden. Diese implementieren alle Interface-Methoden und stellen zu dem eine automatische Validierung bereit.

Die abstrakten Klassen sind immutable.

## Anwendungsbeispiele

### Strings

````PHP
class ClientId extends AbstractStringType
{
    public function isValid( string $value ): bool
    {
        return $value !== '';
    }
}
class ChannelId extends AbstractStringType
{
    public function isValid( string $value ): bool
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

$newClientId = ClientId::fromStringType( $anotherChannelId );
get_class( $newClientId ); //ClientId
$newClientId->toString(); //zalando
(string)$newClientId; //zalando
````

### Integers

````PHP
class Quantity extends AbstractStringType
{
    public function isValid( int $value ): bool
    {
        return $value > 0;
    }
}
$quantityOfFirstItem  = new Quantity( 2 );
$quantityOfSecondItem = new Quantity( 5 );

$totalQuantity = $quantityOfFirstItem->add( $quantityOfSecondItem ); //7
$difference    = $quantityOfFirstItem->subtract( $quantityOfSecondItem ); //throws ValidationException
$difference    = $quantityOfSecondItem->subtract( $quantityOfFirstItem ); //3

$incrementedQuantity = $quantityOfFirstItem->increment( 10 ); //12
````

### Eigene Uuid4-Typen

````PHP
class FulfillmentId
{
    use RepresentingUuid4;
}

$fulfillmentId        = FulfillmentId::generate(); //some UUID4
$anotherFulfillmentId = FulfillmentId::fromString( '9b856c0e-610a-4e38-9ea6-b9ac63cfb521' ); 
````

### Uuid4

````PHP
$uuid4 = (string)Uuid4::generate();
````
