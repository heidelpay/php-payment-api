<?php

namespace Heidelpay\Tests\PhpPaymentApi\Unit\ParameterGroup;

use Codeception\TestCase\Test;
use Heidelpay\PhpPaymentApi\ParameterGroups\RequestParameterGroup as Request;

/**
 * Unit test for RequestParameterGroup
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-payment-api/
 *
 * @author  Jens Richter
 *
 * @package heidelpay\php-payment-api\tests\unit
 */
class RequestParameterGroupTest extends Test
{
    /**
     * Request version getter/setter test
     */
    public function testVersion()
    {
        $Request = new Request();

        $value = '1.2';
        $Request->set('version', $value);

        $this->assertEquals($value, $Request->getVersion());
    }
}
