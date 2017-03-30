<?php

namespace Heidelpay\PhpApi\TransactionTypes;

/**
 * Transaction type reversal
 *
 * This payment type will be used to give an uncharged amount or even parts of
 * it back to the given account. This can be used to lower an amount on an
 * invoice for example.
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
trait ReversalTransactionType
{
    /**
     * Payment type reversal
     *
     * This payment type will be used to give an uncharged amount or even parts of
     * it back to the given account. This can be used to lower an amount on an
     * invoice for example.
     *
     * @param mixed $PaymentReferenceId payment reference id ( unique id of the authorisation)
     *
     * @return \Heidelpay\PhpApi\PaymentMethods\AbstractPaymentMethod
     */
    public function reversal($PaymentReferenceId)
    {
        $this->getRequest()->getPayment()->set('code', $this->_paymentCode . ".RV");
        $this->getRequest()->getFrontend()->set('enabled', 'FALSE');
        $this->getRequest()->getIdentification()->set('referenceId', $PaymentReferenceId);
        $this->prepareRequest();

        return $this;
    }
}
