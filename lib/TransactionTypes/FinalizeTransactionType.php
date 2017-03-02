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
     * @return \Heidelpay\PhpApi\PaymentMethods\AbstractPaymentMethod|boolean
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
