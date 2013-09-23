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
 * Class AddressComponent
 * @package P2\GoogleGeocoding\Geolocation\AddressComponent
 */
class AddressComponent implements AddressComponentInterface
{
    /**
     * @var string
     */
    protected $longName;

    /**
     * @var string
     */
    protected $shortName;

    /**
     * @var array
     */
    protected $types;

    /**
     * @param string $longName
     *
     * @return AddressComponent
     */
    public function setLongName($longName)
    {
        $this->longName = $longName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLongName()
    {
        return $this->longName;
    }

    /**
     * @param string $shortName
     *
     * @return AddressComponent
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;

        return $this;
    }

    /**
     * @return string
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * @param array $types
     *
     * @return AddressComponent
     */
    public function setTypes(array $types)
    {
        $this->types = $types;

        return $this;
    }

    /**
     * @return array
     */
    public function getTypes()
    {
        return $this->types;
    }
}
