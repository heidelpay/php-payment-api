<?php

namespace Heidelpay\Tests\PhpPaymentApi\Unit\ParameterGroup;

use Codeception\TestCase\Test;
use Heidelpay\PhpPaymentApi\Constants\PaymentMethod;
use Heidelpay\PhpPaymentApi\Constants\TransactionType;
use Heidelpay\PhpPaymentApi\ParameterGroups\PaymentParameterGroup as Payment;

/**
 * Unit test for PaymentParameterGroup
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
class PaymentParameterGroupTest extends Test
{
    /**
     * Payment code setter/getter test
     */
    public function testCode()
    {
        $Payment = new Payment();

        $value = PaymentMethod::INVOICE . '.' . TransactionType::RESERVATION;
        $Payment->setCode($value);

        $this->assertEquals($value, $Payment->getCode());
    }
}
