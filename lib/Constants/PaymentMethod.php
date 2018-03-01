<?php

namespace Heidelpay\PhpPaymentApi\Constants;

/**
 * Class for Paymentmethod Code constants
 *
 * @license Use of this software requires acceptance of the License Agreement. See LICENSE file.
 * @copyright Copyright © 2017-present heidelpay GmbH. All rights reserved.
 *
 * @link http://dev.heidelpay.com/php-payment-api
 *
 * @author Stephano Vogel <development@heidelpay.com>
 *
 * @package heidelpay\php-payment-api\constants
 *
 * @since 1.3.0 First time this was introduced.
 */
class PaymentMethod
{
    const CREDIT_CARD = 'CC';
    const DEBIT_CARD = 'DC';
    const DIRECT_DEBIT = 'DD';
    const INVOICE = 'IV';
    const MOBILE_PAYMENT = 'MP';
    const PAYMENT_CARD = 'PC';
    const PREPAYMENT = 'PP';
    const ONLINE_TRANSFER = 'OT';
    const VIRTUAL_ACCOUNT = 'VA';
    const WALLET = 'WT';
    const HIRE_PURCHASE = 'HP';
}
