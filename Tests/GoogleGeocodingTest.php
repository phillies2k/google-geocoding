<?php
/**
 * This file is part of the GoogleGeocoding project.
 *
 * (c) 2013 Philipp Boes <mostgreedy@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace P2\GoogleGeocoding\Tests;

use P2\GoogleGeocoding\GoogleGeocoding;

/**
 * UnitTest GoogleGeocodingTest
 * @package P2\GoogleGeocoding\Tests
 */
class GoogleGeocodingTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GoogleGeocoding
     */
    protected $googleGeocoding;

    /**
     * set up
     */
    protected function setUp()
    {
        parent::setUp();

        $this->googleGeocoding = new GoogleGeocoding();
    }
    
    /**
     * tear down
     */
    protected function tearDown()
    {
        $this->googleGeocoding = null;

        parent::tearDown();
    }

    /**
     * @covers \P2\GoogleGeocoding\GoogleGeocoding::generateUrl
     * @group s
     */
    public function testGenerateUrl()
    {
        $result = $this->googleGeocoding->findByAddress('isartorplatz münchen');
    }
}
