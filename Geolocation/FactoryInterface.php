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

/**
 * Interface FactoryInterface
 * @package P2\GoogleGeocoding\Geolocation
 */
interface FactoryInterface 
{
    /**
     * Returns a geolocation object for the given response object.
     *
     * @param \stdClass $response
     *
     * @return GeolocationInterface
     * @throws InvalidResponseClassException When the given response object cannot be processed by this factory.
     * @throws UnknownResponseClassException When an unknown response class was provided
     */
    public function createFromResponseObject($response);
}
