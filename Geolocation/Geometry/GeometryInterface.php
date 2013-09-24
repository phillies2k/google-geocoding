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
 * Interface GeometryInterface
 * @package P2\GoogleGeocoding\Geolocation\Geometry
 */
interface GeometryInterface 
{
    /**
     * @param LocationInterface $location
     *
     * @return GeometryInterface
     */
    public function setLocation(LocationInterface $location);

    /**
     * @param string $locationType
     *
     * @return GeometryInterface
     */
    public function setLocationType($locationType);

    /**
     * @param ViewportInterface $viewport
     *
     * @return GeometryInterface
     */
    public function setViewport(ViewportInterface $viewport);

    /**
     * @param ViewportInterface $bounds
     *
     * @return GeometryInterface
     */
    public function setBounds(ViewportInterface $bounds);
}
