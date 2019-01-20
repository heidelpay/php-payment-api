<?php

namespace Heidelpay\Tests\PhpPaymentApi\Unit\ParameterGroup;

use Codeception\TestCase\Test;

use Heidelpay\PhpPaymentApi\ParameterGroups\LocationParameterGroup as Location;

/**
 * Unit test for CompanyParameterGroup
 *
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author  David Owusu
 *
 * @package heidelpay\php-payment-api\tests\unit
 */
class LocationParameterGroupTest extends Test
{

    /**
     * @var Location
     */
    protected $location;

    public function _before()
    {
        $this->location = new Location();
    }

    /**
     * Tear down function will remove all registered test doubles (i.e. Mocks)
     */
    // @codingStandardsIgnoreStart
    public function _after()
    {
        // @codingStandardsIgnoreEnd
        $this->location = null;
    }

    /**
     * pobox getter/setter test
     *
     * @test
     */
    public function pobox()
    {
        $value = 'company name';
        $this->location->setPobox($value);

        $this->assertEquals($value, $this->location->getPobox());
    }

    /**
     * street getter/setter test
     *
     * @test
     */
    public function street()
    {
        $value = 'company name';
        $this->location->setStreet($value);

        $this->assertEquals($value, $this->location->getStreet());
    }

    /**
     * zip getter/setter test
     *
     * @test
     */
    public function zip()
    {
        $value = 'company name';
        $this->location->setZip($value);

        $this->assertEquals($value, $this->location->getZip());
    }

    /**
     * city getter/setter test
     *
     * @test
     */
    public function city()
    {
        $value = 'company name';
        $this->location->setCity($value);

        $this->assertEquals($value, $this->location->getCity());
    }

    /**
     * country getter/setter test
     *
     * @test
     */
    public function country()
    {
        $value = 'company name';
        $this->location->setCountry($value);

        $this->assertEquals($value, $this->location->getCountry());
    }
}
