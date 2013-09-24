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
use P2\GoogleGeocoding\Exception\InvalidResponseException;
use P2\GoogleGeocoding\Geolocation\FactoryInterface;
use P2\GoogleGeocoding\Geolocation\GeolocationInterface;

/**
 * Class GoogleGeocoding
 * @package P2\GoogleGeocoding
 */
class GoogleGeocoding
{
    /**
     * @var string
     */
    const SERVICE_URL = 'https://maps.googleapis.com/maps/api/geocode';

    /**
     * @var string
     */
    const FORMAT_JSON = 'json';

    /**
     * @var string
     */
    const FORMAT_XML = 'xml';

    /**
     * @var array
     */
    protected static $formats = array(
        self::FORMAT_JSON,
        self::FORMAT_XML
    );

    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @var string
     */
    protected $format;

    /**
     * @var GeolocationInterface[]
     */
    protected $cache;

    /**
     * @param FactoryInterface $factory
     * @param string $format
     */
    public function __construct(FactoryInterface $factory, $format = self::FORMAT_JSON)
    {
        $this->factory = $factory;
        $this->cache = array();

        $this->setFormat($format);
    }

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
     * Sets the format for geocoding responses.
     *
     * @param string $format
     *
     * @return GoogleGeocoding
     * @throws InvalidFormatException When the given format is not valid.
     */
    public function setFormat($format)
    {
        if (! in_array($format, static::$formats)) {
            throw new InvalidFormatException(sprintf('Invalid google geocoding request format: "%s"', $format));
        }

        $this->format = $format;

        return $this;
    }

    /**
     * Returns the factory for geocoding responses.
     *
     * @return FactoryInterface
     */
    protected function getFactory()
    {
        return $this->factory;
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
    public function generateUrl(array $parameters = array())
    {
        return static::SERVICE_URL . '/' . $this->getFormat() . '?' . http_build_query($parameters);
    }

    /**
     * Returns true when a cache entry was found for the given key, false otherwise.
     *
     * @param string $key
     *
     * @return boolean
     */
    public function isCached($key)
    {
        return isset($this->cache[$key][$this->getFormat()]);
    }

    /**
     * Sets a cache entry.
     *
     * @param string $key
     * @param mixed $content
     *
     * @return $this
     */
    protected function setCache($key, $content)
    {
        if (isset($this->cache[$key])) {
            $this->cache[$key] = array();
        }

        $this->cache[$key][$this->getFormat()] = $content;

        return $this;
    }

    /**
     * Returns the cache entry contents found for the given key, or null.
     *
     * @param string $key
     *
     * @return null|mixed
     */
    public function getCache($key)
    {
        if (! isset($this->cache[$key])) {

            return null;
        }

        if (! isset($this->cache[$key][$this->getFormat()])) {

            return null;
        }

        return $this->cache[$key][$this->getFormat()];
    }

    /**
     * Executes a request against the api. Returns an array of geolocations on success or null on failure.
     * Throws InvalidResponseException when the curl request encountered an error.
     *
     * @param string $url
     * @param boolean $hydrate
     * @param boolean $sensor
     *
     * @return null|GeolocationInterface[]
     * @throws \Exception
     */
    public function request($url, $hydrate = true, $sensor = false)
    {
        $url .= '&sensor=' . ($sensor ? 'true' : 'false');

        if ($this->isCached($url)) {

            return $this->getCache($url);
        }

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

            if ($hydrate === true) {
                $geolocations = array();

                foreach ($response->results as $result) {
                    $geolocations[] = $this->getFactory()->createFromResponseObject($result);
                }

                $this->setCache($url, $geolocations);

                return $geolocations;
            } else {
                $this->setCache($url, $response);

                return $response->results;
            }
        } catch (InvalidResponseException $e) {

            return null;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
