<?php

namespace Heidelpay\PhpPaymentApi\PaymentMethods;

use Heidelpay\PhpPaymentApi\Constants\Brand;
use Heidelpay\PhpPaymentApi\Constants\PaymentMethod;
use Heidelpay\PhpPaymentApi\TransactionTypes\AuthorizeTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\FinalizeTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\RefundTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\ReversalTransactionType;

/**
 * Payolution Invoice
 *
 * heidelpay PHP-API integration for Invoice by Payolution
 *
 * @license Use of this software requires acceptance of the License Agreement. See LICENSE file.
 * @copyright Copyright © 2017-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link https://dev.heidelpay.de/php-api
 *
 * @author Stephano Vogel <development@heidelpay.de>
 *
 * @package heidelpay\php-payment-api\paymentmethods
 */
class PayolutionInvoicePaymentMethod
{
    use BasicPaymentMethodTrait;
    use AuthorizeTransactionType;
    use FinalizeTransactionType;
    use RefundTransactionType;
    use ReversalTransactionType;

    /**
     * @var string Payment Code for this payment method
     */
    protected $paymentCode = PaymentMethod::INVOICE;

    /**
     * @var string Brand Code for this payment method
     */
    protected $brand = Brand::PAYOLUTION_DIRECT;
}
