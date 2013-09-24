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
use P2\GoogleGeocoding\Geolocation\AddressComponent\AddressComponentInterface;
use P2\GoogleGeocoding\Geolocation\GeolocationInterface;
use P2\GoogleGeocoding\Geolocation\Geometry\Geometry;
use P2\GoogleGeocoding\Geolocation\Geometry\GeometryInterface;
use P2\GoogleGeocoding\Geolocation\Geometry\Location;
use P2\GoogleGeocoding\Geolocation\Geometry\LocationInterface;
use P2\GoogleGeocoding\Geolocation\Geometry\Viewport;
use P2\GoogleGeocoding\Geolocation\Geometry\ViewportInterface;

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
    const CLASS_GEOLOCATION = 'P2\GoogleGeocoding\Geolocation\Geolocation';

    /**
     * @var string
     */
    const CLASS_ADDRESS_COMPONENT = 'P2\GoogleGeocoding\AddressComponent\AddressComponent';

    /**
     * @var string
     */
    const CLASS_GEOMETRY = 'P2\GoogleGeocoding\Geolocation\Geometry\Geometry';

    /**
     * @var string
     */
    const CLASS_VIEWPORT = 'P2\GoogleGeocoding\Geolocation\Geometry\Viewport';

    /**
     * @var string
     */
    const CLASS_LOCATION = 'P2\GoogleGeocoding\Geolocation\Geometry\Location';

    /**
     * @var string
     */
    protected $geolocationClass;

    /**
     * @var string
     */
    protected $addressComponentClass;

    /**
     * @var string
     */
    protected $geometryClass;

    /**
     * @var string
     */
    protected $viewportClass;

    /**
     * @var string
     */
    protected $locationClass;

    /**
     * @var string
     */
    protected $format = self::FORMAT_JSON;

    /**
     * @var GeolocationInterface[]
     */
    protected $cache;

    /**
     * @param string $geolocationClass
     * @param string $addressComponentClass
     * @param string $geometryClass
     * @param string $viewportClass
     * @param string $locationClass
     */
    public function __construct(
        $geolocationClass = self::CLASS_GEOLOCATION,
        $addressComponentClass = self::CLASS_ADDRESS_COMPONENT,
        $geometryClass = self::CLASS_GEOMETRY,
        $viewportClass = self::CLASS_VIEWPORT,
        $locationClass = self::CLASS_LOCATION
    ) {
        $this->geolocationClass = $geolocationClass;
        $this->addressComponentClass = $addressComponentClass;
        $this->geometryClass = $geometryClass;
        $this->viewportClass = $viewportClass;
        $this->locationClass = $locationClass;
    }

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
     * Creates a geolocation for the given response.
     *
     * @param \stdClass $response
     *
     * @return GeolocationInterface
     * @throws InvalidResponseClassException
     * @throws UnknownResponseClassException
     */
    protected function createFromResponse($response)
    {
        if (! class_exists($this->geolocationClass)) {
            throw new UnknownResponseClassException(
                sprintf(
                    'Geolocation class not found: "%s"',
                    $this->geolocationClass
                )
            );
        }

        if (! class_exists($this->addressComponentClass)) {
            throw new UnknownResponseClassException(
                sprintf(
                    'Address component class not found: "%s"',
                    $this->addressComponentClass
                )
            );
        }

        if (! class_exists($this->geometryClass)) {
            throw new UnknownResponseClassException(sprintf('Geometry class not found: "%s"', $this->geometryClass));
        }

        if (! class_exists($this->locationClass)) {
            throw new UnknownResponseClassException(sprintf('Location class not found: "%s"', $this->locationClass));
        }

        if (! class_exists($this->viewportClass)) {
            throw new UnknownResponseClassException(sprintf('Viewport class not found: "%s"', $this->viewportClass));
        }

        $geolocation = new $this->geolocationClass();

        if (! $geolocation instanceof GeolocationInterface) {
            throw new InvalidResponseClassException('The geolocation class must implement GeolocationInterface.');
        }

        $geolocation->setTypes($response->types);
        $geolocation->setFormattedAddress($response->formatted_address);

        foreach ($response->address_components as $component) {
            $addressComponent = new $this->addressComponentClass();

            if (! $addressComponent instanceof AddressComponentInterface) {
                throw new InvalidResponseClassException('The geolocation class must implement GeolocationInterface.');
            }

            $addressComponent->setLongName($component->long_name);
            $addressComponent->setShortName($component->short_name);
            $addressComponent->setTypes($component->types);
            $geolocation->addAddressComponent($addressComponent);
        }

        $geometry = new $this->geometryClass();

        if (! $geometry instanceof GeometryInterface) {
            throw new InvalidResponseClassException('The geometry class must implement GeometryInterface.');
        }

        $geometry->setLocationType($response->geometry->location_type);

        $location = new $this->locationClass($response->geometry->location->lat, $response->geometry->location->lng);

        if (! $location instanceof LocationInterface) {
            throw new InvalidResponseClassException('The location class must implement LocationInterface.');
        }

        $geometry->setLocation($location);

        $viewport = new $this->viewportClass(
            new Location($response->geometry->viewport->northeast->lat, $response->geometry->viewport->northeast->lng),
            new Location($response->geometry->viewport->southwest->lat, $response->geometry->viewport->southwest->lng)
        );

        if (! $viewport instanceof ViewportInterface) {
            throw new InvalidResponseClassException('The location class must implement ViewportInterface.');
        }

        $geometry->setViewport($viewport);

        if (isset($response->geometry->bounds)) {

            $bounds = new $this->viewportClass(
                new Location($response->geometry->bounds->northeast->lat, $response->geometry->bounds->northeast->lng),
                new Location($response->geometry->bounds->southwest->lat, $response->geometry->bounds->southwest->lng)
            );

            if (! $bounds instanceof ViewportInterface) {
                throw new InvalidResponseClassException('The location class must implement ViewportInterface.');
            }

            $geometry->setBounds($bounds);
        }

        $geolocation->setGeometry($geometry);

        return $geolocation;
    }
}
