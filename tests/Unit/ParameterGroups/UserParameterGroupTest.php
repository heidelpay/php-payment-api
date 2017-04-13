<?php

namespace Heidelpay\Tests\PhpApi\Unit\ParameterGroup;

use PHPUnit\Framework\TestCase;
use Heidelpay\PhpApi\ParameterGroups\UserParameterGroup as User;

/**
 * Unit test for UserParameterGroup
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
class UserParameterGroupTest extends TestCase
{
    /**
     * Login getter/setter test
     */
    public function testLogin()
    {
        $User = new User();

        $value = '31ha07bc8142c5a171744e5aef11ffd3';
        $User->set('login', $value);

        $this->assertEquals($value, $User->getLogin());
    }

    /**
     * Password getter/setter test
     */
    public function testPassword()
    {
        $User = new User();

        $value = '93167DE7';
        $User->set('pwd', $value);

        $this->assertEquals($value, $User->getPassword());
    }
}
