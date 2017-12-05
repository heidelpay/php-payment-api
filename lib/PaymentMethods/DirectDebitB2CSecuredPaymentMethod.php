<?php

namespace Heidelpay\PhpPaymentApi\PaymentMethods;

use Heidelpay\PhpPaymentApi\TransactionTypes\RegistrationTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\AuthorizeTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\DebitTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\AuthorizeOnRegistrationTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\DebitOnRegistrationTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\RefundTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\ReversalTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\CaptureTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\RebillTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\FinalizeTransactionType;

/**
 * Direct debit secured for b2c payment Class
 *
 * Direct debit secured for b2c  payment method
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
 */
class DirectDebitB2CSecuredPaymentMethod
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
    use FinalizeTransactionType;

    /**
     * Payment code for this payment method
     *
     * @var string payment code
     */
    protected $_paymentCode = 'DD';

    /**
     * Payment brand name for this payment method
     *
     * @var string brand name
     */
    protected $_brand;
}
