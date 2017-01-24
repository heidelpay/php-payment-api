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
}
