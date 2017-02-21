<?php
namespace Heidelpay\Tests\PhpApi\Unit\ParameterGroup;

use PHPUnit\Framework\TestCase;
use Heidelpay\PhpApi\ParameterGroups\ContactParameterGroup as Conatct;

/**
 * Unit test for ContactParameterGroup
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
class ContactParameterGroupTest extends TestCase
{
    /**
     * Email getter/setter test
     */
    public function testEmail()
    {
        $Contact = new Conatct();
        
        $email = "development@heidelpay.de";
        $Contact->set('email', $email);
        
        $this->assertEquals($email, $Contact->getEmail());
    }
    
    /**
     * IP setter/getter test
     */
    public function testIp()
    {
        $Contact = new Conatct();
        
        $ip = "127.0.0.1";
        $Contact->set('ip', $ip);
        
        $this->assertEquals($ip, $Contact->getIp());
    }

    /**
     * Mobile setter/getter test
     *
     * @test
     */
    public function Mobile()
    {
        $Contact = new Conatct();

        $value = "+49 555 22 1340";
        $Contact->set('mobile', $value);

        $this->assertEquals($value, $Contact->getMobile());
    }

    /**
     * Phone setter/getter test
     *
     * @test
     */
    public function Phone()
    {
        $Contact = new Conatct();

        $value = "+49 6221 64 71 400";
        $Contact->set('phone', $value);

        $this->assertEquals($value, $Contact->getPhone());
    }
}
