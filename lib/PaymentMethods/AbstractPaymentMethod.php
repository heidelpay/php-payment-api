<?php

namespace Heidelpay\PhpPaymentApi\PaymentMethods;

use Heidelpay\PhpPaymentApi\TransactionTypes\AuthorizeTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\RefundTransactionType;

/**
 * This classe is the abstract payment method
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author  Jens Richter
 *
 * @package  Heidelpay
 * @subpackage PhpPaymentApi
 * @category PhpPaymentApi
 *
 * @deprecated will be remove if all payment methods are based on BasicPaymentMethodTrait
 */
abstract class AbstractPaymentMethod
{
    use BasicPaymentMethodTrait;
    use AuthorizeTransactionType;
    use RefundTransactionType;

    /**
     * Payment code for this payment method
     *
     * @var string payment code
     */
    protected $_paymentCode = null;

    /**
     * Payment brand name for this payment method
     *
     * @var string brand name
     */
    protected $_brand = null;
}
