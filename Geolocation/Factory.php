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

use P2\GoogleGeocoding\Exception\InvalidResponseClassException;
use P2\GoogleGeocoding\Exception\UnknownResponseClassException;
use P2\GoogleGeocoding\Geolocation\AddressComponent\AddressComponentInterface;
use P2\GoogleGeocoding\Geolocation\Geometry\GeometryInterface;
use P2\GoogleGeocoding\Geolocation\Geometry\LocationInterface;
use P2\GoogleGeocoding\Geolocation\Geometry\ViewportInterface;

/**
 * Class Factory
 * @package P2\GoogleGeocoding\Geolocation
 */
class Factory implements FactoryInterface
{
    /**
     * The default implementation class name of GeolocationInterface.
     *
     * @var string
     */
    const CLASS_GEOLOCATION = 'P2\GoogleGeocoding\Geolocation\Geolocation';

    /**
     * The default implementation class name of AddressComponentInterface.
     *
     * @var string
     */
    const CLASS_ADDRESS_COMPONENT = 'P2\GoogleGeocoding\AddressComponent\AddressComponent';

    /**
     * The default implementation class name of GeometryInterface.
     *
     * @var string
     */
    const CLASS_GEOMETRY = 'P2\GoogleGeocoding\Geolocation\Geometry\Geometry';

    /**
     * The default implementation class name of ViewportInterface.
     *
     * @var string
     */
    const CLASS_VIEWPORT = 'P2\GoogleGeocoding\Geolocation\Geometry\Viewport';

    /**
     * The default implementation class name of LocationInterface.
     *
     * @var string
     */
    const CLASS_LOCATION = 'P2\GoogleGeocoding\Geolocation\Geometry\Location';

    /**
     * @var string
     */
    protected $geolocationClass;

    /**
     * @var string
     */
    protected $addressComponentClass;

    /**
     * @var string
     */
    protected $geometryClass;

    /**
     * @var string
     */
    protected $viewportClass;

    /**
     * @var string
     */
    protected $locationClass;

    /**
     * @param string $geolocationClass
     * @param string $addressComponentClass
     * @param string $geometryClass
     * @param string $viewportClass
     * @param string $locationClass
     */
    public function __construct(
        $geolocationClass = self::CLASS_GEOLOCATION,
        $addressComponentClass = self::CLASS_ADDRESS_COMPONENT,
        $geometryClass = self::CLASS_GEOMETRY,
        $viewportClass = self::CLASS_VIEWPORT,
        $locationClass = self::CLASS_LOCATION
    ) {
        $this->geolocationClass = $geolocationClass;
        $this->addressComponentClass = $addressComponentClass;
        $this->geometryClass = $geometryClass;
        $this->viewportClass = $viewportClass;
        $this->locationClass = $locationClass;
    }

    /**
     * {@inheritDoc}
     */
    public function createFromResponseObject($response)
    {
        if (! class_exists($this->geolocationClass)) {
            throw new UnknownResponseClassException(
                sprintf(
                    'Geolocation class not found: "%s"',
                    $this->geolocationClass
                )
            );
        }

        if (! class_exists($this->addressComponentClass)) {
            throw new UnknownResponseClassException(
                sprintf(
                    'Address component class not found: "%s"',
                    $this->addressComponentClass
                )
            );
        }

        if (! class_exists($this->geometryClass)) {
            throw new UnknownResponseClassException(sprintf('Geometry class not found: "%s"', $this->geometryClass));
        }

        if (! class_exists($this->locationClass)) {
            throw new UnknownResponseClassException(sprintf('Location class not found: "%s"', $this->locationClass));
        }

        if (! class_exists($this->viewportClass)) {
            throw new UnknownResponseClassException(sprintf('Viewport class not found: "%s"', $this->viewportClass));
        }

        $geolocation = new $this->geolocationClass();

        if (! $geolocation instanceof GeolocationInterface) {
            throw new InvalidResponseClassException('The geolocation class must implement GeolocationInterface.');
        }

        $geolocation->setTypes($response->types);
        $geolocation->setFormattedAddress($response->formatted_address);

        foreach ($response->address_components as $component) {
            $addressComponent = new $this->addressComponentClass();

            if (! $addressComponent instanceof AddressComponentInterface) {
                throw new InvalidResponseClassException('The geolocation class must implement GeolocationInterface.');
            }

            $addressComponent->setLongName($component->long_name);
            $addressComponent->setShortName($component->short_name);
            $addressComponent->setTypes($component->types);
            $geolocation->addAddressComponent($addressComponent);
        }

        $geometry = new $this->geometryClass();

        if (! $geometry instanceof GeometryInterface) {
            throw new InvalidResponseClassException('The geometry class must implement GeometryInterface.');
        }

        $geometry->setLocationType($response->geometry->location_type);

        $location = new $this->locationClass($response->geometry->location->lat, $response->geometry->location->lng);

        if (! $location instanceof LocationInterface) {
            throw new InvalidResponseClassException('The location class must implement LocationInterface.');
        }

        $geometry->setLocation($location);

        $viewport = new $this->viewportClass(
            new $this->locationClass(
                $response->geometry->viewport->northeast->lat,
                $response->geometry->viewport->northeast->lng
            ),
            new $this->locationClass(
                $response->geometry->viewport->southwest->lat,
                $response->geometry->viewport->southwest->lng
            )
        );

        if (! $viewport instanceof ViewportInterface) {
            throw new InvalidResponseClassException('The location class must implement ViewportInterface.');
        }

        $geometry->setViewport($viewport);

        if (isset($response->geometry->bounds)) {
            $bounds = new $this->viewportClass(
                new $this->locationClass(
                    $response->geometry->bounds->northeast->lat,
                    $response->geometry->bounds->northeast->lng
                ),
                new $this->locationClass(
                    $response->geometry->bounds->southwest->lat,
                    $response->geometry->bounds->southwest->lng
                )
            );

            if (! $bounds instanceof ViewportInterface) {
                throw new InvalidResponseClassException('The location class must implement ViewportInterface.');
            }

            $geometry->setBounds($bounds);
        }

        $geolocation->setGeometry($geometry);

        return $geolocation;
    }
}
