<?php
/**
 * This file is part of the GoogleGeocoding project.
 *
 * (c) 2013 Philipp Boes <mostgreedy@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace P2\GoogleGeocoding;

use P2\GoogleGeocoding\Exception\InvalidResponseException;
use P2\GoogleGeocoding\Geolocation\AddressComponent\AddressComponent;
use P2\GoogleGeocoding\Geolocation\Geolocation;
use P2\GoogleGeocoding\Geolocation\GeolocationInterface;
use P2\GoogleGeocoding\Geolocation\Geometry\Bounds;
use P2\GoogleGeocoding\Geolocation\Geometry\Geometry;
use P2\GoogleGeocoding\Geolocation\Geometry\Location;
use P2\GoogleGeocoding\Geolocation\Geometry\Viewport;

/**
 * Class GoogleGeocoding
 * @package P2\GoogleGeocoding
 */
class GoogleGeocoding implements GeocodingInterface
{
    const SERVICE_URL = 'https://maps.googleapis.com/maps/api/geocode/json';

    /**
     * Returns an array of geolocations found for the given latitude and longitude.
     *
     * @param float $latitude
     * @param float $longitude
     *
     * @return GeolocationInterface[]
     */
    public function findByCoordinates($latitude, $longitude)
    {
        return $this->request($this->generateUrl(array('latlng' => $latitude.','.$longitude)));
    }

    /**
     * Returns an array of geolocations found for the given address.
     *
     * @param string $address
     *
     * @return GeolocationInterface[]
     */
    public function findByAddress($address)
    {
        return $this->request($this->generateUrl(array('address' => $address)));
    }

    /**
     * Returns the google geocoding api url for the given parameters.
     *
     * @param array $parameters
     *
     * @return string
     */
    protected function generateUrl(array $parameters = array())
    {
        foreach ($parameters as $key => $value) {
            $parameters[$key] = $key . '=' . urlencode($value);
        }

        return static::SERVICE_URL . '?' . implode('&', $parameters) . '&sensor=false';
    }

    /**
     * Executes a request against the api. Returns an array of geolocations on success.
     * Throws InvalidResponseException when the curl request encountered an error.
     *
     * @param string $url
     *
     * @return GeolocationInterface[]
     * @throws InvalidResponseException
     */
    protected function request($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);

        if (false === $response = curl_exec($ch)) {
            throw new InvalidResponseException();
        }

        $response = json_decode($response);

        $geolocations = array();

        foreach ($response->results as $result) {
            $geolocations[] = $this->createFromResponse($result);
        }

        return $geolocations;
    }

    /**
     * Creates a geolocation object for the given response.
     *
     * @param \stdClass $response
     *
     * @return Geolocation
     */
    protected function createFromResponse($response)
    {
        $geolocation = new Geolocation();
        $geolocation->setTypes($response->types);
        $geolocation->setFormattedAddress($response->formatted_address);

        foreach ($response->address_components as $component) {
            $addressComponent = new AddressComponent();
            $addressComponent->setLongName($component->long_name);
            $addressComponent->setShortName($component->short_name);
            $addressComponent->setTypes($component->types);
            $geolocation->addAddressComponent($addressComponent);
        }

        $geometry = new Geometry();
        $geometry->setLocationType($response->geometry->location_type);

        $location = new Location($response->geometry->location->lat, $response->geometry->location->lng);
        $geometry->setLocation($location);

        $viewport = new Viewport(
            new Location($response->geometry->viewport->northeast->lat, $response->geometry->viewport->northeast->lng),
            new Location($response->geometry->viewport->southwest->lat, $response->geometry->viewport->southwest->lng)
        );

        $geometry->setViewport($viewport);

        if (isset($response->geometry->bounds)) {
            $bounds = new Viewport(
                new Location($response->geometry->bounds->northeast->lat, $response->geometry->bounds->northeast->lng),
                new Location($response->geometry->bounds->southwest->lat, $response->geometry->bounds->southwest->lng)
            );
            $geometry->setBounds($bounds);
        }

        $geolocation->setGeometry($geometry);

        return $geolocation;
    }
}
