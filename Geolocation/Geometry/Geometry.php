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
     * {@inheritDoc}
     */
    public function setLocation(LocationInterface $location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return LocationInterface
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     */
    public function setViewport(ViewportInterface $viewport)
    {
        $this->viewport = $viewport;

        return $this;
    }

    /**
     * @return ViewportInterface
     */
    public function getViewport()
    {
        return $this->viewport;
    }

    /**
     * {@inheritDoc}
     */
    public function setBounds(ViewportInterface $bounds)
    {
        $this->bounds = $bounds;

        return $this;
    }

    /**
     * @return ViewportInterface
     */
    public function getBounds()
    {
        return $this->bounds;
    }
}
