<?php

namespace Heidelpay\PhpPaymentApi\PaymentMethods;

use Heidelpay\PhpPaymentApi\Constants\Brand;
use Heidelpay\PhpPaymentApi\Constants\PaymentMethod;
use Heidelpay\PhpPaymentApi\TransactionTypes\AuthorizeOnRegistrationTransactionType as AuthorizeOnInitialization;
use Heidelpay\PhpPaymentApi\TransactionTypes\FinalizeTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\InitializeTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\RefundTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\ReversalTransactionType;

/**
 * Santander hire purchase payment method
 *
 * @license Use of this software requires acceptance of the License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link http://dev.heidelpay.com/heidelpay-php-payment-api/
 *
 * @author Jens Richter
 *
 * @package heidelpay\php-payment-api\paymentmethods
 */
class SantanderHirePurchasePaymentMethod implements PaymentMethodInterface
{
    use AuthorizeOnInitialization;
    use BasicPaymentMethodTrait;
    use InitializeTransactionType;
    use FinalizeTransactionType;
    use RefundTransactionType;
    use ReversalTransactionType;

    /**
     * @var string Payment Code for this payment method
     */
    protected $paymentCode = PaymentMethod::HIRE_PURCHASE;

    /**
     * @var string Brand Code for this payment method
     */
    protected $brand = Brand::SANTANDER_HP;
}
