<?php

namespace Heidelpay\PhpPaymentApi\TransactionTypes;

use Heidelpay\PhpPaymentApi\Constants\TransactionType;

/**
 * Transaction type rebill
 *
 * This payment type will be used to charge a given transaction again. For
 * example, in case of a higher shipping cost. Please make sure that you
 * have the permission of your customer to charge again.
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
trait RebillTransactionType
{
    /**
     * Payment type rebill
     *
     * This payment type will be used to charge a given transaction again. For
     * example, in case of a higher shipping cost. Please make sure that you
     * have the permission of your customer to charge again.
     *
     * @param string $paymentReferenceId (unique id of the debit or capture)
     *
     * @return $this
     *
     * @throws \Heidelpay\PhpPaymentApi\Exceptions\UndefinedTransactionModeException
     */
    public function rebill($paymentReferenceId)
    {
        $this->getRequest()->getPayment()->setCode($this->getPaymentCode() . '.' . TransactionType::REBILL);
        $this->getRequest()->getFrontend()->setEnabled('FALSE');
        $this->getRequest()->getIdentification()->setReferenceId($paymentReferenceId);
        $this->prepareRequest();

        return $this;
    }
}
