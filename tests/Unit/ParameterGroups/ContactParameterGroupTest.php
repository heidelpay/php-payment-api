<?php

namespace Heidelpay\Tests\PhpApi\Unit\ParameterGroup;

use PHPUnit\Framework\TestCase;
use Heidelpay\PhpApi\ParameterGroups\ContactParameterGroup as Conatct;

/**
 * Unit test for ContactParameterGroup
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-api/
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
        $Contact->setEmail($email);

        $this->assertEquals($email, $Contact->getEmail());
    }

    /**
     * IP setter/getter test
     */
    public function testIp()
    {
        $Contact = new Conatct();

        $ip = "127.0.0.1";
        $Contact->setIp($ip);

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
        $Contact->setMobile($value);

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
        $Contact->setPhone($value);

        $this->assertEquals($value, $Contact->getPhone());
    }
}
