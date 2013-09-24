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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
        $this->types = $types;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getTypes()
    {
        return $this->types;
    }
}
