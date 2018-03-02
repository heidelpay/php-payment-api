<?php

namespace Heidelpay\PhpPaymentApi\TransactionTypes;

use Heidelpay\PhpPaymentApi\Constants\TransactionType;

/**
 * Transaction type finalize
 *
 * This payment type will be used to inform heidelpay about goods ship out.
 * Necessary for secured direct debit,secured invoice and Santander.
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
trait FinalizeTransactionType
{
    /**
     * Payment type finalize
     *
     * This payment type will be used to inform heidelpay about goods ship out.
     * Necessary for secured direct debit,secured invoice and Santander.
     *
     * @param string $paymentReferenceId reference id (uniqe id of the debit or capture)
     *
     * @return $this
     *
     * @throws \Heidelpay\PhpPaymentApi\Exceptions\UndefinedTransactionModeException
     */
    public function finalize($paymentReferenceId)
    {
        $this->getRequest()->getPayment()->setCode($this->getPaymentCode() . '.' . TransactionType::FINALIZE);
        $this->getRequest()->getFrontend()->setEnabled('FALSE');
        $this->getRequest()->getIdentification()->setReferenceId($paymentReferenceId);
        $this->prepareRequest();

        return $this;
    }
}
