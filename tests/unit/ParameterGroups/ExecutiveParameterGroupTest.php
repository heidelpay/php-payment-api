<?php

namespace Heidelpay\Tests\PhpPaymentApi\Unit\ParameterGroup;

use Codeception\TestCase\Test;
use Heidelpay\PhpPaymentApi\ParameterGroups\ExecutiveParameterGroup as Executive;
use Heidelpay\PhpPaymentApi\ParameterGroups\ExecutiveParameterGroup;
use Heidelpay\PhpPaymentApi\ParameterGroups\HomeParameterGroup;

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
class ExecutiveParameterGroupTest extends Test
{

    /**
     * @var ExecutiveParameterGroup
     */
    protected $executive;

    public function _before()
    {
        $this->executive = new Executive();
    }

    /**
     * Tear down function will remove all registered test doubles (i.e. Mocks)
     */
    // @codingStandardsIgnoreStart
    public function _after()
    {
        // @codingStandardsIgnoreEnd
        $this->executive = null;
    }

    /**
     * function getter/setter test
     *
     * @test
     */
    public function functionTest()
    {
        $value = 'company name';
        $this->executive->setFunction($value);

        $this->assertEquals($value, $this->executive->getFunction());
    }
    
    /**
     * salutation getter/setter test
     *
     * @test
     */
    public function salutation()
    {
        $value = 'company name';
        $this->executive->setSalutation($value);

        $this->assertEquals($value, $this->executive->getSalutation());
    }

    /**
     * given getter/setter test
     *
     * @test
     */
    public function given()
    {
        $value = 'company name';
        $this->executive->setGiven($value);

        $this->assertEquals($value, $this->executive->getGiven());
    }

    /**
     * family getter/setter test
     *
     * @test
     */
    public function family()
    {
        $value = 'company name';
        $this->executive->setFamily($value);

        $this->assertEquals($value, $this->executive->getFamily());
    }

    /**
     * birthdate getter/setter test
     *
     * @test
     */
    public function birthdate()
    {
        $value = 'company name';
        $this->executive->setBirthdate($value);

        $this->assertEquals($value, $this->executive->getBirthdate());
    }

    /**
     * email getter/setter test
     *
     * @test
     */
    public function email()
    {
        $value = 'company name';
        $this->executive->setEmail($value);

        $this->assertEquals($value, $this->executive->getEmail());
    }

    /**
     * phone getter/setter test
     *
     * @test
     */
    public function phone()
    {
        $value = 'company name';
        $this->executive->setPhone($value);

        $this->assertEquals($value, $this->executive->getPhone());
    }

    /**
     * home getter/setter test
     *
     * @test
     */
    public function home()
    {
        $value = new HomeParameterGroup();
        $this->executive->setHome($value);

        $this->assertEquals($value, $this->executive->getHome());
    }
}
