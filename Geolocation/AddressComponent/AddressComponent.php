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
use P2\GoogleGeocoding\Geolocation\Component;

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
    protected $types = array();

    /**
     * {@inheritDoc}
     */
    public function setLongName($longName)
    {
        $this->longName = $longName;

        return $this;
    }

    /**
     * Returns the long name for this address component.
     *
     * @return string
     */
    public function getLongName()
    {
        return $this->longName;
    }

    /**
     * {@inheritDoc}
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;

        return $this;
    }

    /**
     * Returns the short name for this address component.
     *
     * @return string
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * {@inheritDoc}
     */
    public function setTypes(array $types)
    {
        $components = Component::getComponents();

        foreach ($types as $type) {
            if (! in_array($type, $components)) {
                throw new UnknownComponentTypeException(sprintf('Unknown component type "%s"', $type));
            }
        }

        $this->types = $types;

        return $this;
    }

    /**
     * Returns the types for this address component.
     *
     * @return array
     */
    public function getTypes()
    {
        return $this->types;
    }
}
