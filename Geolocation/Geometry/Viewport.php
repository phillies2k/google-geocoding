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
 * Class Viewport
 * @package P2\GoogleGeocoding\Geolocation\Geometry
 */
class Viewport implements ViewportInterface
{
    /**
     * @var LocationInterface
     */
    protected $northeast;

    /**
     * @var LocationInterface
     */
    protected $southwest;

    /**
     * @param LocationInterface $northeast
     * @param LocationInterface $southwest
     */
    public function __construct(LocationInterface $northeast, LocationInterface $southwest)
    {
        $this->northeast = $northeast;
        $this->southwest = $southwest;
    }

    /**
     * {@inheritDoc}
     */
    public function setNortheast(LocationInterface $northeast)
    {
        $this->northeast = $northeast;

        return $this;
    }

    /**
     * @return LocationInterface
     */
    public function getNortheast()
    {
        return $this->northeast;
    }

    /**
     * {@inheritDoc}
     */
    public function setSouthwest(LocationInterface $southwest)
    {
        $this->southwest = $southwest;

        return $this;
    }

    /**
     * @return LocationInterface
     */
    public function getSouthwest()
    {
        return $this->southwest;
    }
}
