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

/**
 * Interface AddressComponentInterface
 * @package P2\GoogleGeocoding\Geolocation\AddressComponent
 */
interface AddressComponentInterface 
{
    /**
     * Returns the long name for this address component.
     *
     * @return string
     */
    public function getLongName();

    /**
     * Returns the short name for this address component.
     *
     * @return string
     */
    public function getShortName();

    /**
     * Returns the types for this address component.
     *
     * @return array
     */
    public function getTypes();
}
