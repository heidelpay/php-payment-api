<?php

namespace Heidelpay\PhpPaymentApi\TransactionTypes;

/**
 * Transaction type finalize
 *
 * This payment type will be used to inform heidelpay about goods ship out.
 * Necessary for secured direct debit,secured invoice and Santander.
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
     * @return \Heidelpay\PhpPaymentApi\PaymentMethods\AbstractPaymentMethod
     */
    public function finalize($PaymentReferenceId)
    {
        $this->getRequest()->getPayment()->set('code', $this->_paymentCode . ".FI");
        $this->getRequest()->getFrontend()->set('enabled', 'FALSE');
        $this->getRequest()->getIdentification()->set('referenceId', $PaymentReferenceId);
        $this->prepareRequest();

        return $this;
    }
}
