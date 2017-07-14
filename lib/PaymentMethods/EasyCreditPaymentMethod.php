<?php

namespace Heidelpay\PhpApi\PaymentMethods;

use Heidelpay\PhpApi\TransactionTypes\AuthorizeOnRegistrationTransactionType as AuthorizeOnInitialization;
use Heidelpay\PhpApi\TransactionTypes\FinalizeTransactionType;
use Heidelpay\PhpApi\TransactionTypes\InitializeTransactionType;
use Heidelpay\PhpApi\TransactionTypes\RefundTransactionType;
use Heidelpay\PhpApi\TransactionTypes\ReversalTransactionType;

/**
 * Easy Credit Payment Method
 *
 * @license Use of this software requires acceptance of the License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link https://dev.heidelpay.de/php-api
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
