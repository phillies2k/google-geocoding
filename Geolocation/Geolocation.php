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
 * Class Geolocation
 * @package P2\GoogleGeocoding\Geolocation
 */
class Geolocation implements GeolocationInterface
{
    /**
     * @var AddressComponentInterface[]
     */
    protected $addressComponents;

    /**
     * @var string
     */
    protected $formattedAddress;

    /**
     * @var GeometryInterface
     */
    protected $geometry;

    /**
     * @var array
     */
    protected $types;

    /**
     * {@inheritDoc}
     */
    public function addAddressComponent(AddressComponentInterface $addressComponent)
    {
        $this->addressComponents[] = $addressComponent;

        return $this;
    }

    /**
     * Returns an address component for this geolocation by its type.
     *
     * @param string $type
     *
     * @return AddressComponentInterface
     */
    public function getAddressComponent($type)
    {
        foreach ($this->addressComponents as $addressComponent) {
            if (in_array($type, $addressComponent->getTypes())) {

                return $addressComponent;
            }
        }

        return null;
    }

    /**
     * Returns an array of address components for this geolocation.
     *
     * @return AddressComponentInterface[]
     */
    public function getAddressComponents()
    {
        return $this->addressComponents;
    }

    /**
     * {@inheritDoc}
     */
    public function setFormattedAddress($formattedAddress)
    {
        $this->formattedAddress = $formattedAddress;

        return $this;
    }

    /**
     * Returns the formatted address string.
     *
     * @return string
     */
    public function getFormattedAddress()
    {
        return $this->formattedAddress;
    }

    /**
     * {@inheritDoc}
     */
    public function setGeometry(GeometryInterface $geometry)
    {
        $this->geometry = $geometry;

        return $this;
    }

    /**
     * Returns the geometry for this geolocation.
     *
     * @return GeometryInterface
     */
    public function getGeometry()
    {
        return $this->geometry;
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
     * Returns an array of types for this geolocation.
     *
     * @return array
     */
    public function getTypes()
    {
        return $this->types;
    }
}
