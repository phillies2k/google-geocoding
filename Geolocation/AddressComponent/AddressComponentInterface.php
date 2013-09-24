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
     * @param $longName
     *
     * @return AddressComponentInterface
     */
    public function setLongName($longName);

    /**
     * @param $shortName
     *
     * @return AddressComponentInterface
     */
    public function setShortName($shortName);

    /**
     * @param array $types
     *
     * @return AddressComponentInterface
     * @throws UnknownComponentTypeException When an unknown type was given.
     */
    public function setTypes(array $types);
}
