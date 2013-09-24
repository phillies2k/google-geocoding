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
     * Returns an address component for this geolocation by its type.
     *
     * @param string $type
     *
     * @return AddressComponentInterface
     */
    public function getAddressComponent($type);

    /**
     * Returns an array of address components for this geolocation.
     *
     * @return AddressComponentInterface[]
     */
    public function getAddressComponents();

    /**
     * Sets the formatted address for this geolocation.
     *
     * @param string $formattedAddress
     *
     * @return GeolocationInterface
     */
    public function setFormattedAddress($formattedAddress);

    /**
     * Returns the formatted address string.
     *
     * @return string
     */
    public function getFormattedAddress();

    /**
     * Sets the geometry for this geolocation.
     *
     * @param GeometryInterface $geometry
     *
     * @return GeolocationInterface
     */
    public function setGeometry(GeometryInterface $geometry);

    /**
     * Returns the geometry for this geolocation.
     *
     * @return GeometryInterface
     */
    public function getGeometry();

    /**
     * Sets the types for this geolocation.
     *
     * @param array $types
     *
     * @return GeolocationInterface
     */
    public function setTypes(array $types);

    /**
     * Returns an array of types for this geolocation.
     *
     * @return array
     */
    public function getTypes();
}
