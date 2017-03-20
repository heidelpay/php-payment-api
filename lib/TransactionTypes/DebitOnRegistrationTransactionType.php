<?php

namespace Heidelpay\PhpApi\TransactionTypes;

/**
 * Transaction type debit on registration
 *
 * This payment type will charge the given account directly. The debit is
 * related to a registration.
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
trait DebitOnRegistrationTransactionType
{
    /**
     * Payment type debit on registration
     *
     * This payment type will charge the given account directly. The debit is
     * related to a registration.
     *
     * @param string payment reference id ( unique id of the registration
     * @param mixed $PaymentReferenceId
     *
     * @return \Heidelpay\PhpApi\PaymentMethods\AbstractPaymentMethod|boolean
     */
    public function debitOnRegistration($PaymentReferenceId)
    {
        $this->getRequest()->getPayment()->set('code', $this->_paymentCode . ".DB");
        $this->getRequest()->getIdentification()->set('referenceId', $PaymentReferenceId);
        $this->prepareRequest();

        return $this;
    }
}
