<?php

namespace Heidelpay\PhpApi\PaymentMethods;

use Heidelpay\PhpApi\TransactionTypes\RegistrationTransactionType;
use Heidelpay\PhpApi\TransactionTypes\AuthorizeTransactionType;
use Heidelpay\PhpApi\TransactionTypes\DebitTransactionType;
use Heidelpay\PhpApi\TransactionTypes\AuthorizeOnRegistrationTransactionType;
use Heidelpay\PhpApi\TransactionTypes\DebitOnRegistrationTransactionType;
use Heidelpay\PhpApi\TransactionTypes\RefundTransactionType;
use Heidelpay\PhpApi\TransactionTypes\ReversalTransactionType;
use Heidelpay\PhpApi\TransactionTypes\CaptureTransactionType;
use Heidelpay\PhpApi\TransactionTypes\RebillTransactionType;

/**
 * PayPal Payment Class
 *
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
class PayPalPaymentMethod
{
    use BasicPaymentMethodTrait;
    use RegistrationTransactionType;
    use AuthorizeTransactionType;
    use DebitTransactionType;
    use AuthorizeOnRegistrationTransactionType;
    use DebitOnRegistrationTransactionType;
    use RefundTransactionType;
    use ReversalTransactionType;
    use CaptureTransactionType;
    use RebillTransactionType;
    
    /**
     * Payment code for this payment method
     *
     * @var string payment code
     */
    protected $_paymentCode = 'VA';

    /**
     * Payment brand name for this payment method
     *
     * @var string brand name
     */
    protected $_brand = "PAYPAL";
}
