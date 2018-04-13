<?php

namespace Heidelpay\PhpPaymentApi\PaymentMethods;

use Heidelpay\PhpPaymentApi\Constants\Brand;
use Heidelpay\PhpPaymentApi\Constants\PaymentMethod;
use Heidelpay\PhpPaymentApi\TransactionTypes\AuthorizeTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\RefundTransactionType;

/**
 * EPS is a payment method in Austria.
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-payment-api/
 *
 * @author  Ronja Wann
 *
 * @package heidelpay\php-payment-api\paymentmethods
 */
class EPSPaymentMethod implements PaymentMethodInterface
{
    use BasicPaymentMethodTrait;
    use AuthorizeTransactionType;
    use RefundTransactionType;

    /**
     * @var string Payment Code for this payment method
     */
    protected $paymentCode = PaymentMethod::ONLINE_TRANSFER;

    /**
     * @var string Brand Code for this payment method
     */
    protected $brand = Brand::EPS;
}
