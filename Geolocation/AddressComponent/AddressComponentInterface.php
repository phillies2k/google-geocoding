<?php
/**
 * This file is part of the GoogleGeocoding project.
 *
 * (c) 2013 Philipp Boes <mostgreedy@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace P2\GoogleGeocoding\Geolocation\AddressComponent;

use P2\GoogleGeocoding\Exception\UnknownComponentTypeException;

/**
 * Interface AddressComponentInterface
 * @package P2\GoogleGeocoding\Geolocation\AddressComponent
 */
interface AddressComponentInterface 
{
    /**
     * Sets the long name for this address component.
     *
     * @param string $longName
     *
     * @return AddressComponentInterface
     */
    public function setLongName($longName);

    /**
     * Sets the short name for this address component.
     *
     * @param string $shortName
     *
     * @return AddressComponentInterface
     */
    public function setShortName($shortName);

    /**
     * Sets the types for this address component.
     *
     * @param array $types
     *
     * @return AddressComponentInterface
     * @throws UnknownComponentTypeException When an unknown type was given.
     */
    public function setTypes(array $types);
}
