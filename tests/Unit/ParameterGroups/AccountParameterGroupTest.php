<?php
namespace Heidelpay\Tests\PhpApi\Unit\ParameterGroup;

use PHPUnit\Framework\TestCase;
use Heidelpay\PhpApi\ParameterGroups\AccountParameterGroup as Account;

/**
 * Unit test for AccountParameterGroup
 *
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
    public function Bank()
    {
        $Account = new Account();

        $value = "Heidelpay";
        $Account->set("bank", $value);

        $this->assertEquals($value, $Account->getBank());
    }

    /**
     * BankName getter/setter test
     *
     * @test
     */
    public function BankName()
    {
        $Account = new Account();

        $value = "Sofort";
        $Account->set("bankname", $value);

        $this->assertEquals($value, $Account->getBankName());
    }

    /**
     * Brand getter/setter test
     */
    public function testBrand()
    {
        $Account = new Account();
    
        $value = "Master";
        $Account->set("brand", $value);
    
        $this->assertEquals($value, $Account->getBrand());
    }

    /**
     * Bic getter/setter test
     *
     * @test
     */
    public function Bic()
    {
        $Account = new Account();

        $value = "COBADEFFXXX";
        $Account->set("bic", $value);

        $this->assertEquals($value, $Account->getBic());
    }

    /**
     * Country getter/setter test
     *
     * @test
     */
    public function Country()
    {
        $Account = new Account();

        $value = "DE";
        $Account->set("country", $value);

        $this->assertEquals($value, $Account->getCountry());
    }
    
    /**
     * Holder getter/setter test
     */
    public function testHolder()
    {
        $Account = new Account();
        
        $name = "Hans Meister";
        $Account->set("holder", $name);
        
        $this->assertEquals($name, $Account->getHolder());
    }
    
    /**
     * Iban getter/setter test
     */
    public function testIban()
    {
        $Account = new Account();
    
        $value = "DE89370400440532013000";
        $Account->set("iban", $value);
    
        $this->assertEquals($value, $Account->getIban());
    }

    /**
     * Sepa Mandate Identification getter/setter test
     *
     * @test
     */
    public function Identification()
    {
        $Account = new Account();

        $value = "3516.0799.6864";
        $Account->set("identification", $value);

        $this->assertEquals($value, $Account->getIdentification());
    }
    
    /**
     * Expiry month getter/setter test
     */
    public function testExpiryMonth()
    {
        $Account = new Account();
    
        $value = "05";
        $Account->set("expiry_month", $value);
    
        $this->assertEquals($value, $Account->getExpiryMonth());
    }
    
    /**
     * Expiry year getter/setter test
     */
    public function testExpiryYear()
    {
        $Account = new Account();
    
        $value = "2080";
        $Account->set("expiry_year", $value);
    
        $this->assertEquals($value, $Account->getExpiryYear());
    }
    
    /**
     * Number getter/setter test
     */
    public function testNumber()
    {
        $Account = new Account();
    
        $value = "1234567890";
        $Account->set("number", $value);
    
        $this->assertEquals($value, $Account->getNumber());
    }

    /**
     * Verification getter/setter test
     *
     * @test
     */
    public function Verification()
    {
        $Account = new Account();

        $value = "***";
        $Account->set("verification", $value);

        $this->assertEquals($value, $Account->getVerification());
    }
}
