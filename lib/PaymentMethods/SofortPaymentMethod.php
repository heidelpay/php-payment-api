<?php

namespace Heidelpay\PhpApi\PaymentMethods;

use Heidelpay\PhpApi\TransactionTypes\AuthorizeTransactionType;
use Heidelpay\PhpApi\TransactionTypes\RefundTransactionType;

/**
 * Sofort Payment Class
 *
 * Sofort is a payment method from SOFORT GmbH also known as sofortüberweisung in Germany.
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  https://dev.heidelpay.de/PhpApi
 *
 * @author  Jens Richter
 *
 * @package  Heidelpay
 * @subpackage PhpApi
 * @category PhpApi
 */
class SofortPaymentMethod
{
    use BasicPaymentMethodTrait;
    use AuthorizeTransactionType;
    use RefundTransactionType;

    /**
     * Payment code for this payment method
     *
     * @var string payment code
     */
    protected $_paymentCode = 'OT';

    /**
     * Payment brand name for this payment method
     *
     * @var string brand name
     */
    protected $_brand = "SOFORT";
}
