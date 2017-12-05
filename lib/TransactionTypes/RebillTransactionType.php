<?php

namespace Heidelpay\PhpPaymentApi\TransactionTypes;

/**
 * Transaction type rebill
 *
 * This payment type will be used to charge a given transaction again. For
 * example, in case of a higher shipping cost. Please make sure that you
 * have the permission of your customer to charge again.
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
trait RebillTransactionType
{
    /**
     * Payment type rebill
     *
     * This payment type will be used to charge a given transaction again. For
     * example, in case of a higher shipping cost. Please make sure that you
     * have the permission of your customer to charge again.
     *
     * @param string $PaymentReferenceId ( unique id of the debit or capture )
     *
     * @return \Heidelpay\PhpPaymentApi\PaymentMethods\AbstractPaymentMethod|boolean
     */
    public function rebill($PaymentReferenceId)
    {
        $this->getRequest()->getPayment()->set('code', $this->_paymentCode . ".RB");
        $this->getRequest()->getFrontend()->set('enabled', 'FALSE');
        $this->getRequest()->getIdentification()->set('referenceId', $PaymentReferenceId);
        $this->prepareRequest();

        return $this;
    }
}
