<?php

namespace Heidelpay\Tests\PhpPaymentApi\Unit\ParameterGroup;

use Codeception\TestCase\Test;

use Heidelpay\PhpPaymentApi\ParameterGroups\HomeParameterGroup as Home;

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
class HomeParameterGroupTest extends Test
{

    /**
     * @var Home
     */
    protected $home;

    public function _before()
    {
        $this->home = new Home();
    }

    /**
     * Tear down function will remove all registered test doubles (i.e. Mocks)
     */
    // @codingStandardsIgnoreStart
    public function _after()
    {
        // @codingStandardsIgnoreEnd
        $this->home = null;
    }

    /**
     * street getter/setter test
     *
     * @test
     */
    public function street()
    {
        $value = 'company name';
        $this->home->setStreet($value);

        $this->assertEquals($value, $this->home->getStreet());
    }

    /**
     * zip getter/setter test
     *
     * @test
     */
    public function zip()
    {
        $value = 'company name';
        $this->home->setZip($value);

        $this->assertEquals($value, $this->home->getZip());
    }

    /**
     * city getter/setter test
     *
     * @test
     */
    public function city()
    {
        $value = 'company name';
        $this->home->setCity($value);

        $this->assertEquals($value, $this->home->getCity());
    }

    /**
     * country getter/setter test
     *
     * @test
     */
    public function country()
    {
        $value = 'company name';
        $this->home->setCountry($value);

        $this->assertEquals($value, $this->home->getCountry());
    }
}
