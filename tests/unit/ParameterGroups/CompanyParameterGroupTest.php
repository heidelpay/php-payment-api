<?php

namespace Heidelpay\Tests\PhpPaymentApi\Unit\ParameterGroup;

use Codeception\TestCase\Test;
use Heidelpay\PhpPaymentApi\Constants\CommercialSector;
use Heidelpay\PhpPaymentApi\ParameterGroups\CompanyParameterGroup as Company;
use Heidelpay\PhpPaymentApi\ParameterGroups\CompanyParameterGroup;
use Heidelpay\PhpPaymentApi\ParameterGroups\ExecutiveParameterGroup;
use Heidelpay\PhpPaymentApi\ParameterGroups\LocationParameterGroup as Location;
use Heidelpay\Tests\PhpPaymentApi\Helper\Executive;

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
class CompanyParameterGroupTest extends Test
{

    /**
     * @var Company
     */
    protected $company;

    public function _before()
    {
        $this->company = new Company();
        $executive = new Executive();

        $this->company->addExecutive(...$executive->getExecutiveOneArray());
        $this->company->addExecutive(...$executive->getExecutiveTwoArray());
        $this->company->addExecutive(...$executive->getExecutiveOneArray());
    }

    /**
     * Tear down function will remove all registered test doubles (i.e. Mocks)
     */
    // @codingStandardsIgnoreStart
    public function _after()
    {
        // @codingStandardsIgnoreEnd
        $this->company = null;
    }

    /**
     * companyName getter/setter test
     *
     * @test
     */
    public function companyName()
    {
        $value = 'company name';
        $this->company->setCompanyname($value);

        $this->assertEquals($value, $this->company->getCompanyname());
    }
    
    /**
     * registrationType getter/setter test
     *
     * @test
     */
    public function registrationType()
    {
        $value = 'REGISTRED';
        $this->company->setRegistrationType($value);

        $this->assertEquals($value, $this->company->getRegistrationType());
    }

    /**
     * commercialRegisterNumber getter/setter test
     *
     * @test
     */
    public function commercialRegisterNumber()
    {
        $value = 'company name';
        $this->company->setCommercialRegisterNumber($value);

        $this->assertEquals($value, $this->company->getCommercialRegisterNumber());
    }
    
    /**
     * vatId getter/setter test
     *
     * @test
     */
    public function vatId()
    {
        $value = 'company name';
        $this->company->setVatId($value);

        $this->assertEquals($value, $this->company->getVatId());
    }

    /**
     * executive getter/setter test
     *
     * @test
     */
    public function executive()
    {
        $value = [
            new ExecutiveParameterGroup()
        ];

        $this->company->setExecutive($value);
        $this->assertEquals($value, $this->company->getExecutive());
    }

    /**
     * @param ExecutiveParameterGroup $expected
     * @param mixed                   $index
     * @dataProvider executiveGetterIndexShouldWorkAsExpectedDataProvider
     * @test
     */
    public function executiveGetterIndexShouldWorkAsExpected($expected, $index)
    {
        $this->assertEquals($expected, $this->company->getExecutive($index));
    }

    public function executiveGetterIndexShouldWorkAsExpectedDataProvider()
    {
        $executiveFixture = new Executive();

        $testCompany = new CompanyParameterGroup();
        $testCompany->addExecutive(...$executiveFixture->getExecutiveOneArray());
        $testCompany->addExecutive(...$executiveFixture->getExecutiveTwoArray());
        $testCompany->addExecutive(...$executiveFixture->getExecutiveOneArray());

        return [
            'index exists' => [$testCompany->executive[0], 0],
            'index of second executive' => [$testCompany->executive[1], 1],
            'last element exists' => [$testCompany->executive[2], 2],
            'index element doesnt exists' => [null, 3],
            'negative index' => [null, -3],
            'index is boolean true' => [null, true],
            'index is boolean false' => [null, false],
        ];
    }

    /**
     * CommercialSector getter/setter test
     *
     * @test
     */
    public function commercialSector()
    {
        $value = CommercialSector::COMPUTER_PROGRAMMING_CONSULTANCY_AND_RELATED_ACTIVITIES;
        $this->company->setCommercialSector($value);

        $this->assertEquals($value, $this->company->getCommercialSector());
    }

    /**
     * location getter/setter test
     *
     * @test
     */
    public function location()
    {
        $value = new Location();
        $this->company->setLocation($value);

        $this->assertEquals($value, $this->company->getLocation());
    }

    /**
     * @dataProvider functionShouldBeSetAsExpectedDataProvider
     * @test
     *
     * @param mixed $expected
     * @param mixed $function
     */
    public function functionShouldBeSetAsExpected($expected, $function)
    {
        $company = new Company();
        $company->addExecutive('', '', '', '', '', '', '', '', '', '', $function);

        $this->assertEquals($expected, $company->getExecutive()[0]->getFunction());
    }

    public function functionShouldBeSetAsExpectedDataProvider()
    {
        return [
            'function is null' => ['OWNER', null],
            'function is empty string' => ['OWNER', ''],
            'function is false' => ['OWNER', false],
            'function is true' => ['OWNER', true],
            'function is string' => ['hello world', 'hello world'],
            'function is number' => ['OWNER', 42],
        ];
    }
}
