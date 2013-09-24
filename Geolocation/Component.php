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

/**
 * Class Component
 * @package P2\GoogleGeocoding\Geolocation
 */
final class Component
{
    const STREET_ADDRESS              = 'street_address';
    const ROUTE                       = 'route';
    const INTERSECTION                = 'intersection';
    const POLITICAL                   = 'political';
    const COUNTRY                     = 'country';
    const ADMINISTRATIVE_AREA_LEVEL_1 = 'administrative_area_level_1';
    const ADMINISTRATIVE_AREA_LEVEL_2 = 'administrative_area_level_2';
    const ADMINISTRATIVE_AREA_LEVEL_3 = 'administrative_area_level_3';
    const COLLOQUIAL_AREA             = 'colloquial_area';
    const LOCALITY                    = 'locality';
    const SUBLOCALITY                 = 'sublocality';
    const NEIGHBORHOOD                = 'neighborhood';
    const PREMISE                     = 'premise';
    const SUBPREMISE                  = 'subpremise';
    const POSTAL_CODE                 = 'postal_code';
    const NATURAL_FEATURE             = 'natural_feature';
    const AIRPORT                     = 'airport';
    const PARK                        = 'park';
    const POINT_OF_INTEREST           = 'point_of_interest';
    const POST_BOX                    = 'post_box';
    const STREET_NUMBER               = 'street_number';
    const FLOOR                       = 'floor';
    const ROOM                        = 'room';

    /**
     * @return array
     */
    public static function getComponents()
    {
        return array(
            self::STREET_ADDRESS,
            self::ROUTE,
            self::INTERSECTION,
            self::POLITICAL,
            self::COUNTRY,
            self::ADMINISTRATIVE_AREA_LEVEL_1,
            self::ADMINISTRATIVE_AREA_LEVEL_2,
            self::ADMINISTRATIVE_AREA_LEVEL_3,
            self::COLLOQUIAL_AREA,
            self::LOCALITY,
            self::SUBLOCALITY,
            self::NEIGHBORHOOD,
            self::PREMISE,
            self::SUBPREMISE,
            self::POSTAL_CODE,
            self::NATURAL_FEATURE,
            self::AIRPORT,
            self::PARK,
            self::POINT_OF_INTEREST,
            self::POST_BOX,
            self::STREET_NUMBER,
            self::FLOOR,
            self::ROOM
        );
    }
}
