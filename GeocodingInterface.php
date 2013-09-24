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
use P2\GoogleGeocoding\Geolocation\GeolocationInterface;

/**
 * Interface GeocodingInterface
 * @package P2\GoogleGeocoding
 */
interface GeocodingInterface 
{
    /**
     * Returns an array of geolocations found for the given latitude and longitude.
     *
     * @param float $latitude
     * @param float $longitude
     *
     * @return GeolocationInterface[]
     */
    public function findByCoordinates($latitude, $longitude);

    /**
     * Returns an array of geolocations found for the given address.
     *
     * @param string $address
     *
     * @return GeolocationInterface[]
     */
    public function findByAddress($address);

    /**
     * Sets the response class to hydrate. This class must implement the GeolocationInterface.
     *
     * @param string $responseClass
     *
     * @return GeocodingInterface
     */
    public function setResponseClass($responseClass);

    /**
     * Sets the format for geocoding responses.
     *
     * @param string $format
     *
     * @return GeocodingInterface
     * @throws InvalidFormatException When the given format is not valid.
     */
    public function setFormat($format);
}
