Google Geocoding
================

v0.9.7


### Full featured example

```php

<?php

use P2\GoogleGeocoding\Geolocation\Component;
use P2\GoogleGeocoding\Geolocation\Factory;
use P2\GoogleGeocoding\GoogleGeocoding;

// the address to look up
$address = '123 5th Avenue ny';

// create the response factory
// you may specify the class names for each response object
$factory = new Factory(
    'Acme\CustomBundle\Document\Geolocation',
    'Acme\CustomBundle\Document\Geolocation\AddressComponent',
    'Acme\CustomBundle\Document\Geolocation\Geometry',
    'Acme\CustomBundle\Document\Geolocation\Viewport',
    'Acme\CustomBundle\Document\Geolocation\Location',
);

// initialize the service
$googleGeocoding = new GoogleGeocoding($factory, GoogleGeocoding::FORMAT_JSON);

header('Content-Type: text/plain; charset=utf-8');

echo "\n\nGeocoding (looking up: {$address})\n\n";

// geocoding
$geolocations = $googleGeocoding->findByAddress($address);

foreach ($geolocations as $geolocation) {
    echo $geolocation->getFormattedAddress() . "\n";
}

echo "\n\nReverse Geocoding:\n\n";

// reverse geo coding
$geolocations = $googleGeocoding->findByCoordinates(12.128462, 34.924239);

foreach ($geolocations as $geolocation) {
    echo "* " . $geolocation->getFormattedAddress() . "\n";
}

```