<?php

namespace Heidelpay\PhpApi\TransactionTypes;

/**
 * Transaction type  authorisation on registration
 *
 *  This payment type will be used to make an authorisation on a given registration.
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
trait AuthorizeOnRegistrationTransactionType
{
    /**
     * Payment type authorisation on registration
     *
     * This payment type will be used to make an authorisation on a given registration.
     *
     * @param string payment reference id (unique id of the registration)
     * @param mixed $PaymentReferenceId
     *
     * @return \Heidelpay\PhpApi\PaymentMethods\AbstractPaymentMethod|boolean
     */
    public function authorizeOnRegistration($PaymentReferenceId)
    {
        $this->getRequest()->getPayment()->set('code', $this->_paymentCode . ".PA");
        $this->getRequest()->getIdentification()->set('referenceId', $PaymentReferenceId);
        $this->prepareRequest();

        return $this;
    }
}
