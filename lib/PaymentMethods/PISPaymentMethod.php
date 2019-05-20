<?php
/**
 * Implementation of heidelpay PIS payment method (aka. FlexiPay).
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2019-present heidelpay GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-payment-api/
 *
 * @author  Simon Gabriel
 *
 * @package heidelpay\php-payment-api\paymentmethods
 */
namespace Heidelpay\PhpPaymentApi\PaymentMethods;

use Heidelpay\PhpPaymentApi\Constants\Brand;
use Heidelpay\PhpPaymentApi\Constants\PaymentMethod;
use Heidelpay\PhpPaymentApi\TransactionTypes\AuthorizeTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\RefundTransactionType;
class PISPaymentMethod implements PaymentMethodInterface
{
    use BasicPaymentMethodTrait;
    use AuthorizeTransactionType;
    use RefundTransactionType;

    /** @var string $paymentCode Payment Code for this payment method */
    protected $paymentCode = PaymentMethod::ONLINE_TRANSFER;

    /** @var string $brand Brand Code for this payment method */
    protected $brand = Brand::PIS;
}
