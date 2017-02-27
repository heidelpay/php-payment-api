<?php

namespace Heidelpay\PhpApi\PaymentMethods;

use Heidelpay\PhpApi\TransactionTypes\AuthorizeTransactionType;
use Heidelpay\PhpApi\TransactionTypes\ReversalTransactionType;
use Heidelpay\PhpApi\TransactionTypes\RefundTransactionType;
use Heidelpay\PhpApi\TransactionTypes\FinalizeTransactionType;

/**
 * Invoice b2c secured Payment Class
 *
 * This payment method is the secured  b2c invoice.
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
class InvoiceB2CSecuredPaymentMethod
{
    use BasicPaymentMethodTrait;
    use AuthorizeTransactionType;
    use ReversalTransactionType;
    use RefundTransactionType;
    use FinalizeTransactionType;

    /**
     * Payment code for this payment method
     *
     * @var string payment code
     */
    protected $_paymentCode = 'IV';

    /**
     * Payment brand name for this payment method
     *
     * @var string brand name
     */
    protected $_brand = null;
}
