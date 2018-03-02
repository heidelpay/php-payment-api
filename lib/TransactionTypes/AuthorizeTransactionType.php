<?php

namespace Heidelpay\PhpPaymentApi\TransactionTypes;

use Heidelpay\PhpPaymentApi\Constants\TransactionType;

/**
 * Transaction type authorize
 *
 * Depending on the payment method this type normally means that the amount
 * of the given account will only be authorized. In case of payment methods
 * like Sofort and Giropay (so called online payments) this type will be
 * used just to get the redirect to their systems.
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-payment-api/
 *
 * @author  Jens Richter
 *
 * @package heidelpay\php-payment-api\transaction-types
 */
trait AuthorizeTransactionType
{
    /**
     * Payment type authorisation
     *
     * Depending on the payment method this type normally means that the amount
     * of the given account will only be authorized. In case of payment methods
     * like Sofort and Giropay (so called online payments) this type will be
     * used just to get the redirect to their systems.
     *
     * @return $this
     *
     * @throws \Heidelpay\PhpPaymentApi\Exceptions\UndefinedTransactionModeException
     */
    public function authorize()
    {
        $this->getRequest()->getPayment()->setCode($this->getPaymentCode() . '.' . TransactionType::RESERVATION);
        $this->prepareRequest();

        return $this;
    }
}
