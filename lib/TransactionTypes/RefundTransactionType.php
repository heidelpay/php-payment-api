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
     * @param string payment reference id ( uniqe id of the debit or capture)
     * @param mixed $PaymentReferenceId
     *
     * @return \Heidelpay\PhpApi\PaymentMethodes\AbstractPaymentMethod|boolean
     */
    public function refund($PaymentReferenceId)
    {
        $this->getRequest()->getPayment()->set('code', $this->_paymentCode . ".RF");
        $this->getRequest()->getCriterion()->set('payment_method', $this->getClassName());
        $this->getRequest()->getFrontend()->set('enabled', 'FALSE');
        $this->getRequest()->getIdentification()->set('referenceId', $PaymentReferenceId);
        if ($this->_brand !== null) {
            $this->getRequest()->getAccount()->set('brand', $this->_brand);
        }

        $uri = $this->getPaymentUrl();
        $this->_requestArray = $this->getRequest()->prepareRequest();

        if ($this->_dryRun === false and $uri !== null and is_array($this->_requestArray)) {
            list($this->_responseArray, $this->_response) = $this->getRequest()->send($uri, $this->_requestArray,
                $this->getAdapter());
        }

        return $this;
    }
}
