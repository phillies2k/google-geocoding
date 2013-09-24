<?php
/**
 * This file is part of the GoogleGeocoding project.
 *
 * (c) 2013 Philipp Boes <mostgreedy@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace P2\GoogleGeocoding\Geolocation;

use P2\GoogleGeocoding\Exception\UnknownComponentTypeException;
use P2\GoogleGeocoding\Geolocation\AddressComponent\AddressComponentInterface;
use P2\GoogleGeocoding\Geolocation\Geometry\GeometryInterface;

/**
 * Interface GeolocationInterface
 * @package P2\GoogleGeocoding
 */
interface GeolocationInterface
{
    /**
     * Adds an address component for this geolocation.
     *
     * @param AddressComponentInterface $addressComponent
     *
     * @return GeolocationInterface
     */
    public function addAddressComponent(AddressComponentInterface $addressComponent);

    /**
     * Sets the formatted address for this geolocation.
     *
     * @param string $formattedAddress
     *
     * @return GeolocationInterface
     */
    public function setFormattedAddress($formattedAddress);

    /**
     * Sets the geometry for this geolocation.
     *
     * @param GeometryInterface $geometry
     *
     * @return GeolocationInterface
     */
    public function setGeometry(GeometryInterface $geometry);

    /**
     * Sets the types for this geolocation.
     *
     * @param array $types
     *
     * @return GeolocationInterface
     * @throws UnknownComponentTypeException When an unknown type was given.
     */
    public function setTypes(array $types);
}
