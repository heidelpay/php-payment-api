<?php

namespace Heidelpay\Tests\PhpPaymentApi\Unit\ParameterGroup;

use Codeception\TestCase\Test;
use Heidelpay\PhpPaymentApi\ParameterGroups\AccountParameterGroup as Account;

/**
 * Unit test for AccountParameterGroup
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author  Jens Richter
 *
 * @package heidelpay\php-payment-api\tests\unit
 */
class AccountParameterGroupTest extends Test
{
    /**
     * Bank getter/setter test
     *
     * @test
     */
    public function bank()
    {
        $account = new Account();

        $value = 'Heidelpay';
        /** @noinspection PhpDeprecationInspection */
        $account->setBank($value);

        /** @noinspection PhpDeprecationInspection */
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

        $value = 'Sofort';
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

        $value = 'Master';
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

        $value = 'COBADEFFXXX';
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

        $value = 'DE';
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

        $name = 'Hans Meister';
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

        $value = 'DE89370400440532013000';
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

        $value = '3516.0799.6864';
        $account->setIdentification($value);

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

        $value = '05';
        $account->setExpiryMonth($value);

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

        $value = '2080';
        $account->setExpiryYear($value);

        $this->assertEquals($value, $account->getExpiryYear());
    }

    /**
     * Number getter/setter test
     *
     * @test
     *
     */
    public function number()
    {
        $account = new Account();

        $value = '1234567890';
        $account->setNumber($value);

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

        $value = '***';
        $account->setVerification($value);

        $this->assertEquals($value, $account->getVerification());
    }

    /**
     * @test
     */
    public function jsonSerializeTest()
    {
        $account = new Account();

        $this->assertNotEmpty($account->jsonSerialize());
        $this->assertArrayHasKey('bank', $account->jsonSerialize());
        $this->assertArrayHasKey('bankname', $account->jsonSerialize());
        $this->assertArrayHasKey('brand', $account->jsonSerialize());
        $this->assertArrayHasKey('bic', $account->jsonSerialize());
        $this->assertArrayHasKey('country', $account->jsonSerialize());
        $this->assertArrayHasKey('expiry_month', $account->jsonSerialize());
        $this->assertArrayHasKey('expiry_year', $account->jsonSerialize());
        $this->assertArrayHasKey('holder', $account->jsonSerialize());
        $this->assertArrayHasKey('iban', $account->jsonSerialize());
        $this->assertArrayHasKey('identification', $account->jsonSerialize());
        $this->assertArrayHasKey('number', $account->jsonSerialize());
        $this->assertArrayHasKey('verification', $account->jsonSerialize());
    }

    /**
     * Test to verify that toJson returns valid JSON.
     *
     * @test
     */
    public function toJsonTest()
    {
        $account = new Account();
        $this->assertJson($account->toJson());
    }
}
