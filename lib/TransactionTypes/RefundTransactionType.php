<?php

namespace Heidelpay\PhpApi\TransactionTypes;

/**
 * Transaction type refund
 *
 * This payment type will be used to give a charge amount or even parts of
 * it back to the given account.
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  https://dev.heidelpay.de/PhpApi
 *
 * @author  Jens Richter
 *
 * @package  Heidelpay
 * @subpackage PhpApi
 * @category PhpApi
 */
trait RefundTransactionType
{
    /**
     * Payment type refund
     *
     * This payment type will be used to give a charge amount or even parts of
     * it back to the given account.
     *
     * @param mixed $PaymentReferenceId payment reference id ( uniqe id of the debit or capture)
     *
     * @return \Heidelpay\PhpApi\PaymentMethods\AbstractPaymentMethod
     */
    public function refund($PaymentReferenceId)
    {
        $this->getRequest()->getPayment()->set('code', $this->_paymentCode . ".RF");
        $this->getRequest()->getFrontend()->set('enabled', 'FALSE');
        $this->getRequest()->getIdentification()->set('referenceId', $PaymentReferenceId);
        $this->prepareRequest();

        return $this;
    }
}
