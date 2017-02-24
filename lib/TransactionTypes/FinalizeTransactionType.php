<?php

namespace Heidelpay\PhpApi\TransactionTypes;

/**
 * Transaction type finalize
 *
 * This payment type will be used to inform heidelpay about goods ship out.
 * Necessary for secured direct debit,secured invoice and Santander.
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
trait FinalizeTransactionType
{
    /**
     * Payment type finalize
     *
     * This payment type will be used to inform heidelpay about goods ship out.
     * Necessary for secured direct debit,secured invoice and Santander.
     *
     * @param mixed $PaymentReferenceId reference id ( uniqe id of the debit or capture)
     *
     * @return \Heidelpay\PhpApi\PaymentMethodes\AbstractPaymentMethod|boolean
     */
    public function finalize($PaymentReferenceId)
    {
        $this->getRequest()->getPayment()->set('code', $this->_paymentCode . ".FI");
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
