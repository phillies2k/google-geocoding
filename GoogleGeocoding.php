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

use P2\GoogleGeocoding\Exception\InvalidFormatException;
use P2\GoogleGeocoding\Exception\InvalidResponseClassException;
use P2\GoogleGeocoding\Exception\InvalidResponseException;
use P2\GoogleGeocoding\Exception\UnknownResponseClassException;
use P2\GoogleGeocoding\Geolocation\AddressComponent\AddressComponent;
use P2\GoogleGeocoding\Geolocation\GeolocationInterface;
use P2\GoogleGeocoding\Geolocation\Geometry\Geometry;
use P2\GoogleGeocoding\Geolocation\Geometry\Location;
use P2\GoogleGeocoding\Geolocation\Geometry\Viewport;

/**
 * Class GoogleGeocoding
 * @package P2\GoogleGeocoding
 */
class GoogleGeocoding implements GeocodingInterface
{
    /**
     * @var string
     */
    const FORMAT_JSON = 'json';

    /**
     * @var string
     */
    const FORMAT_XML = 'xml';

    /**
     * @var string
     */
    const SERVICE_URL = 'https://maps.googleapis.com/maps/api/geocode';

    /**
     * @var string
     */
    protected $responseClass = 'P2\GoogleGeocoding\Geolocation\Geolocation';

    /**
     * @var string
     */
    protected $format = self::FORMAT_JSON;

    /**
     * @var GeolocationInterface[]
     */
    protected $cache;

    /**
     * {@inheritDoc}
     */
    public function findByCoordinates($latitude, $longitude)
    {
        return $this->request($this->generateUrl(array('latlng' => $latitude.','.$longitude)));
    }

    /**
     * {@inheritDoc}
     */
    public function findByAddress($address)
    {
        return $this->request($this->generateUrl(array('address' => $address)));
    }

    /**
     * @param string $responseClass
     *
     * @return GoogleGeocoding
     */
    public function setResponseClass($responseClass)
    {
        $this->responseClass = $responseClass;

        return $this;
    }

    /**
     * @return string
     */
    protected function getResponseClass()
    {
        return $this->responseClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setFormat($format)
    {
        if ($format !== static::FORMAT_JSON || $format !== static::FORMAT_XML) {
            throw new InvalidFormatException(sprintf('Invalid google geocoding request format: "%s"', $format));
        }

        $this->format = $format;

        return $this;
    }

    /**
     * Returns the format of geocoding responses.
     *
     * @return string
     */
    protected function getFormat()
    {
        return $this->format;
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
        return static::SERVICE_URL . '/' . $this->getFormat() . '?' . http_build_query($parameters);
    }

    /**
     * Executes a request against the api. Returns an array of geolocations on success or null on failure.
     * Throws InvalidResponseException when the curl request encountered an error.
     *
     * @param string $url
     *
     * @return null|GeolocationInterface[]
     * @throws \Exception
     */
    protected function request($url)
    {
        if (! isset($this->cache[$url])) {
            try {
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HEADER, false);

                if (false === $response = curl_exec($ch)) {
                    throw new InvalidResponseException();
                }

                switch ($this->getFormat()) {
                    case static::FORMAT_JSON:
                        $response = json_encode($response);
                        break;
                    case static::FORMAT_XML:
                        $response = simplexml_load_string($response);
                        break;
                }

                $geolocations = array();

                foreach ($response->results as $result) {
                    $geolocations[] = $this->createFromResponse($result);
                }

                $this->cache[$url] = $geolocations;
            } catch (InvalidResponseException $e) {

                return null;
            } catch (\Exception $e) {
                throw $e;
            }
        }

        return $this->cache[$url];
    }

    /**
     * Creates a geolocation object for the given response.
     *
     * @param \stdClass $response
     *
     * @return GeolocationInterface
     * @throws InvalidResponseClassException
     * @throws UnknownResponseClassException
     */
    protected function createFromResponse($response)
    {
        $class = $this->getResponseClass();

        if (! class_exists($class)) {
            throw new UnknownResponseClassException(sprintf('Response class not found: "%s"', $class));
        }

        $geolocation = new $class();

        if (! $geolocation instanceof GeolocationInterface) {
            throw new InvalidResponseClassException('The response class must implement GeolocationInterface.');
        }

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
