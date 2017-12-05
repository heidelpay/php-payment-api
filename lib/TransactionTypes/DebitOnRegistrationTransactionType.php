<?php

namespace Heidelpay\PhpPaymentApi\TransactionTypes;

/**
 * Transaction type debit on registration
 *
 * This payment type will charge the given account directly. The debit is
 * related to a registration.
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
trait DebitOnRegistrationTransactionType
{
    /**
     * Payment type debit on registration
     *
     * This payment type will charge the given account directly. The debit is
     * related to a registration.
     *
     * @param string $PaymentReferenceId ( unique id of the registration
     *
     * @return \Heidelpay\PhpPaymentApi\PaymentMethods\AbstractPaymentMethod|boolean
     */
    public function debitOnRegistration($PaymentReferenceId)
    {
        $this->getRequest()->getPayment()->set('code', $this->_paymentCode . ".DB");
        $this->getRequest()->getIdentification()->set('referenceId', $PaymentReferenceId);
        $this->prepareRequest();

        return $this;
    }
}
