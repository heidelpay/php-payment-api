<?php

namespace Heidelpay\PhpPaymentApi\TransactionTypes;

use Heidelpay\PhpPaymentApi\Constants\TransactionType;

/**
 * Transaction type debit on registration
 *
 * This payment type will charge the given account directly. The debit is
 * related to a registration.
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
trait DebitOnRegistrationTransactionType
{
    /**
     * Payment type debit on registration
     *
     * This payment type will charge the given account directly. The debit is
     * related to a registration.
     *
     * @param string $paymentReferenceId ( unique id of the registration
     *
     * @return $this
     *
     * @throws \Heidelpay\PhpPaymentApi\Exceptions\UndefinedTransactionModeException
     */
    public function debitOnRegistration($paymentReferenceId)
    {
        $this->getRequest()->getPayment()->setCode($this->getPaymentCode() . '.' . TransactionType::DEBIT);
        $this->getRequest()->getIdentification()->setReferenceId($paymentReferenceId);
        $this->prepareRequest();

        return $this;
    }
}
