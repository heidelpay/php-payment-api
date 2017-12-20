<?php

namespace Heidelpay\Tests\PhpPaymentApi\Unit\ParameterGroup;

use Codeception\TestCase\Test;
use Heidelpay\PhpPaymentApi\ParameterGroups\ContactParameterGroup as Contact;

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
 * @package heidelpay\php-payment-api\tests\unit
 */
class ContactParameterGroupTest extends Test
{
    /**
     * Email getter/setter test
     */
    public function testEmail()
    {
        $Contact = new Contact();

        $email = 'development@heidelpay.de';
        $Contact->setEmail($email);

        $this->assertEquals($email, $Contact->getEmail());
    }

    /**
     * IP setter/getter test
     */
    public function testIp()
    {
        $Contact = new Contact();

        $ipAddress = '127.0.0.1';
        $Contact->setIp($ipAddress);

        $this->assertEquals($ipAddress, $Contact->getIp());
    }

    /**
     * Mobile setter/getter test
     *
     * @test
     */
    public function mobile()
    {
        $Contact = new Contact();

        $value = '+49 555 22 1340';
        $Contact->setMobile($value);

        $this->assertEquals($value, $Contact->getMobile());
    }

    /**
     * Phone setter/getter test
     *
     * @test
     */
    public function phone()
    {
        $Contact = new Contact();

        $value = '+49 6221 64 71 400';
        $Contact->setPhone($value);

        $this->assertEquals($value, $Contact->getPhone());
    }
}
