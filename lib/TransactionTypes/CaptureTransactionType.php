<?php

namespace Heidelpay\PhpPaymentApi\TransactionTypes;

/**
 * Transaction type capture
 *
 * You can charge a given authorisation by capturing the transaction.
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author  Jens Richter
 *
 * @package  Heidelpay
 * @subpackage PhpPaymentApi
 * @category PhpPaymentApi
 */
trait CaptureTransactionType
{
    /**
     * Payment type capture
     *
     * You can charge a given authorisation by capturing the transaction.
     *
     * @param string $PaymentReferenceId ( unique id of the authorisation )
     *
     * @return \Heidelpay\PhpPaymentApi\PaymentMethods\AbstractPaymentMethod
     */
    public function capture($PaymentReferenceId)
    {
        $this->getRequest()->getPayment()->set('code', $this->_paymentCode . ".CP");
        $this->getRequest()->getFrontend()->set('enabled', 'FALSE');
        $this->getRequest()->getIdentification()->set('referenceId', $PaymentReferenceId);
        $this->prepareRequest();

        return $this;
    }
}
