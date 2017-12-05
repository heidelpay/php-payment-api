<?php

namespace Heidelpay\PhpPaymentApi\TransactionTypes;

/**
 * Transaction type  authorisation on registration
 *
 *  This payment type will be used to make an authorisation on a given registration.
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
trait AuthorizeOnRegistrationTransactionType
{
    /**
     * Payment type authorisation on registration
     *
     * This payment type will be used to make an authorisation on a given registration.
     *
     * @param string $PaymentReferenceId (unique id of the registration)
     *
     * @return \Heidelpay\PhpPaymentApi\PaymentMethods\AbstractPaymentMethod
     */
    public function authorizeOnRegistration($PaymentReferenceId)
    {
        $this->getRequest()->getPayment()->set('code', $this->_paymentCode . ".PA");
        $this->getRequest()->getIdentification()->set('referenceId', $PaymentReferenceId);
        $this->prepareRequest();

        return $this;
    }
}
