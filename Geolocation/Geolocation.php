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
     * @param AddressComponentInterface[] $addressComponents
     *
     * @return GeolocationInterface
     */
    public function setAddressComponents(array $addressComponents)
    {
        foreach ($addressComponents as $addressComponent) {
            $this->addAddressComponent($addressComponent);
        }

        return $this;
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
        foreach ($types as $type) {
            $this->addType($type);
        }

        return $this;
    }

    /**
     * @param string $type
     *
     * @return GeolocationInterface
     * @throws \P2\GoogleGeocoding\Exception\UnknownComponentTypeException
     */
    public function addType($type)
    {
        if (! in_array($type, Component::getComponents())) {
            throw new UnknownComponentTypeException(sprintf('Unknown component type "%s"', $type));
        }

        if (! in_array($type, $this->types)) {
            $this->types[] = $type;
        }

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
