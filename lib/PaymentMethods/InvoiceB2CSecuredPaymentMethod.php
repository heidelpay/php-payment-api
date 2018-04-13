<?php

namespace Heidelpay\PhpPaymentApi\PaymentMethods;

use Heidelpay\PhpPaymentApi\Constants\PaymentMethod;
use Heidelpay\PhpPaymentApi\TransactionTypes\AuthorizeTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\ReversalTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\RefundTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\FinalizeTransactionType;

/**
 * This payment method is for the secured B2C invoice.
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-payment-api/
 *
 * @author  Jens Richter
 *
 * @package heidelpay\php-payment-api\paymentmethods
 */
class InvoiceB2CSecuredPaymentMethod implements PaymentMethodInterface
{
    use BasicPaymentMethodTrait;
    use AuthorizeTransactionType;
    use ReversalTransactionType;
    use RefundTransactionType;
    use FinalizeTransactionType;

    /**
     * @var string Payment Code for this payment method
     */
    protected $paymentCode = PaymentMethod::INVOICE;
}
