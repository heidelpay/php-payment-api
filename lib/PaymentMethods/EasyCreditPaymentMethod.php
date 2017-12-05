<?php

namespace Heidelpay\PhpPaymentApi\PaymentMethods;

use Heidelpay\PhpPaymentApi\TransactionTypes\AuthorizeOnRegistrationTransactionType as AuthorizeOnInitialization;
use Heidelpay\PhpPaymentApi\TransactionTypes\FinalizeTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\InitializeTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\RefundTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\ReversalTransactionType;

/**
 * Easy Credit Payment Method
 *
 * @license Use of this software requires acceptance of the License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author Stephano Vogel
 *
 * @package heidelpay\php-api\paymentmethods\easycredit
 */
class EasyCreditPaymentMethod implements PaymentMethodInterface
{
    use AuthorizeOnInitialization;
    use BasicPaymentMethodTrait;
    use InitializeTransactionType;
    use FinalizeTransactionType;
    use RefundTransactionType;
    use ReversalTransactionType;

    /**
     * Payment code for this payment method
     *
     * @var string payment code
     */
    protected $_paymentCode = 'HP';

    /**
     * Payment brand name for this payment method
     *
     * @var string brand name
     */
    protected $_brand = 'EASYCREDIT';
}
