<?php
/**
 * This file is part of the GoogleGeocoding project.
 *
 * (c) 2013 Philipp Boes <mostgreedy@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace P2\GoogleGeocoding\Geolocation\Geometry;

/**
 * Class Geometry
 * @package P2\GoogleGeocoding\Geolocation\Geometry
 */
class Geometry implements GeometryInterface
{
    /**
     * @var Location
     */
    protected $location;

    /**
     * @var string
     */
    protected $locationType;

    /**
     * @var Viewport
     */
    protected $viewport;

    /**
     * @var Viewport
     */
    protected $bounds;

    /**
     * @param \P2\GoogleGeocoding\Geolocation\Geometry\Location $location
     *
     * @return Geometry
     */
    public function setLocation(Location $location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return \P2\GoogleGeocoding\Geolocation\Geometry\Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param string $locationType
     *
     * @return Geometry
     */
    public function setLocationType($locationType)
    {
        $this->locationType = $locationType;

        return $this;
    }

    /**
     * @return string
     */
    public function getLocationType()
    {
        return $this->locationType;
    }

    /**
     * @param \P2\GoogleGeocoding\Geolocation\Geometry\Viewport $viewport
     *
     * @return Geometry
     */
    public function setViewport(Viewport $viewport)
    {
        $this->viewport = $viewport;

        return $this;
    }

    /**
     * @return \P2\GoogleGeocoding\Geolocation\Geometry\Viewport
     */
    public function getViewport()
    {
        return $this->viewport;
    }

    /**
     * @param \P2\GoogleGeocoding\Geolocation\Geometry\Viewport $bounds
     *
     * @return Geometry
     */
    public function setBounds(Viewport $bounds)
    {
        $this->bounds = $bounds;

        return $this;
    }

    /**
     * @return \P2\GoogleGeocoding\Geolocation\Geometry\Viewport
     */
    public function getBounds()
    {
        return $this->bounds;
    }
}
