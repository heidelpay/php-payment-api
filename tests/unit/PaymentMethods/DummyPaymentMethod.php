<?php
/**
 * This class is used to test the perform trait test.
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/
 *
 * @author  Simon Gabriel <development@heidelpay.de>
 *
 * @package Heidelpay\Tests\PhpPaymentApi\Unit\PaymentMethods
 */
namespace Heidelpay\Tests\PhpPaymentApi\unit\PaymentMethods;

use Heidelpay\PhpPaymentApi\PaymentMethods\BasicPaymentMethodTrait;

class DummyPaymentMethod
{
    use BasicPaymentMethodTrait;
}
