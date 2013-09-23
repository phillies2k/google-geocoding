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
 * Interface ViewportInterface
 * @package P2\GoogleGeocoding\Geolocation\Geometry
 */
interface ViewportInterface 
{
    /**
     * @return LocationInterface
     */
    public function getNortheast();

    /**
     * @return LocationInterface
     */
    public function getSouthwest();
}
