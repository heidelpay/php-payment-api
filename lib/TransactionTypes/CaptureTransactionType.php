<?php
namespace Heidelpay\PhpApi\TransactionTypes;

/**
 * Transaction type capture
 *
 * You can charge a given authorisation by capturing the transaction.
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
trait CaptureTransactionType
{
    /**
     * Payment type capture
     *
     * You can charge a given authorisation by capturing the transaction.
     *
     * @param string payment reference id ( unique id of the authorisation )
     * @param mixed $PaymentReferenceId
     *
     * @return \Heidelpay\PhpApi\PaymentMethods\AbstractPaymentMethod|boolean
     */
    public function capture($PaymentReferenceId)
    {
        $this->getRequest()->getPayment()->set('code', $this->_paymentCode.".CP");
        $this->getRequest()->getFrontend()->set('enabled', 'FALSE');
        $this->getRequest()->getIdentification()->set('referenceId', $PaymentReferenceId);
        $this->prepareRequest();
        return $this;
    }
}
