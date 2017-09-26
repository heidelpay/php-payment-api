<?php

namespace Heidelpay\Tests\PhpApi\Unit\ParameterGroup;

use PHPUnit\Framework\TestCase;
use Heidelpay\PhpApi\ParameterGroups\AccountParameterGroup as Account;

/**
 * Unit test for AccountParameterGroup
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  https://dev.heidelpay.de/PhpApi
 *
 * @author  Jens Richter
 *
 * @category unittest
 */
class AccountParameterGroupTest extends TestCase
{
    /**
     * Bank getter/setter test
     *
     * @test
     */
    public function bank()
    {
        $account = new Account();

        $value = "Heidelpay";
        $account->setBank($value);

        $this->assertEquals($value, $account->getBank());
    }

    /**
     * BankName getter/setter test
     *
     * @test
     */
    public function bankName()
    {
        $account = new Account();

        $value = "Sofort";
        $account->setBankName($value);

        $this->assertEquals($value, $account->getBankName());
    }

    /**
     * Brand getter/setter test
     *
     * @test
     */
    public function brand()
    {
        $account = new Account();

        $value = "Master";
        $account->setBrand($value);

        $this->assertEquals($value, $account->getBrand());
    }

    /**
     * Bic getter/setter test
     *
     * @test
     */
    public function bic()
    {
        $account = new Account();

        $value = "COBADEFFXXX";
        $account->setBic($value);

        $this->assertEquals($value, $account->getBic());
    }

    /**
     * Country getter/setter test
     *
     * @test
     */
    public function country()
    {
        $account = new Account();

        $value = "DE";
        $account->setCountry($value);

        $this->assertEquals($value, $account->getCountry());
    }

    /**
     * Holder getter/setter test
     *
     * @test
     */
    public function holder()
    {
        $account = new Account();

        $name = "Hans Meister";
        $account->setHolder($name);

        $this->assertEquals($name, $account->getHolder());
    }

    /**
     * Iban getter/setter test
     *
     * @test
     */
    public function iban()
    {
        $account = new Account();

        $value = "DE89370400440532013000";
        $account->setIban($value);

        $this->assertEquals($value, $account->getIban());
    }

    /**
     * Sepa Mandate Identification getter/setter test
     *
     * @test
     */
    public function identification()
    {
        $account = new Account();

        $value = "3516.0799.6864";
        $account->set("identification", $value);

        $this->assertEquals($value, $account->getIdentification());
    }

    /**
     * Expiry month getter/setter test
     *
     * @test
     */
    public function expiryMonth()
    {
        $account = new Account();

        $value = "05";
        $account->set("expiry_month", $value);

        $this->assertEquals($value, $account->getExpiryMonth());
    }

    /**
     * Expiry year getter/setter test
     *
     * @test
     */
    public function expiryYear()
    {
        $account = new Account();

        $value = "2080";
        $account->set("expiry_year", $value);

        $this->assertEquals($value, $account->getExpiryYear());
    }

    /**
     * Number getter/setter test
     *
     * @test
     *
     */
    public function testNumber()
    {
        $account = new Account();

        $value = "1234567890";
        $account->set("number", $value);

        $this->assertEquals($value, $account->getNumber());
    }

    /**
     * Verification getter/setter test
     *
     * @test
     */
    public function verification()
    {
        $account = new Account();

        $value = "***";
        $account->set("verification", $value);

        $this->assertEquals($value, $account->getVerification());
    }
}
