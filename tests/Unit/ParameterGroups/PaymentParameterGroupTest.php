<?php

namespace Heidelpay\Tests\PhpApi\Unit\ParameterGroup;

use PHPUnit\Framework\TestCase;
use Heidelpay\PhpApi\ParameterGroups\PaymentParameterGroup as Payment;

/**
 * Unit test for PaymentParameterGroup
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
class PaymentParameterGroupTest extends TestCase
{
    /**
     * Payment code setter/getter test
     */
    public function testCode()
    {
        $Payment = new Payment();

        $value = 'IV.PA';
        $Payment->set('code', $value);

        $this->assertEquals($value, $Payment->getCode());
    }
}
