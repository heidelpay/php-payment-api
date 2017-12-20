<?php

namespace Heidelpay\Tests\PhpPaymentApi\Unit\ParameterGroup;

use Codeception\TestCase\Test;
use Heidelpay\PhpPaymentApi\ParameterGroups\UserParameterGroup as User;

/**
 * Unit test for UserParameterGroup
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
class UserParameterGroupTest extends Test
{
    /**
     * Login getter/setter test
     */
    public function testLogin()
    {
        $User = new User();

        $value = '31ha07bc8142c5a171744e5aef11ffd3';
        $User->setLogin($value);

        $this->assertEquals($value, $User->getLogin());
    }

    /**
     * Password getter/setter test
     */
    public function testPassword()
    {
        $User = new User();

        $value = '93167DE7';
        $User->setPassword($value);

        $this->assertEquals($value, $User->getPassword());
    }
}
