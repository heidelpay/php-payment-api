<?php

namespace Heidelpay\PhpPaymentApi\TransactionTypes;

use Heidelpay\PhpPaymentApi\Constants\TransactionType;

/**
 * Transaction type  authorisation on registration
 *
 * This payment type will be used to make an authorisation on a given registration.
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author  Jens Richter
 *
 * @package heidelpay\php-payment-api\transaction-types
 */
trait AuthorizeOnRegistrationTransactionType
{
    /**
     * Payment type authorisation on registration
     * This payment type will be used to make an authorisation on a given registration.
     *
     * @param string $paymentReferenceId (unique id of the registration)
     *
     * @return $this
     *
     * @throws \Heidelpay\PhpPaymentApi\Exceptions\UndefinedTransactionModeException
     */
    public function authorizeOnRegistration($paymentReferenceId)
    {
        $this->getRequest()->getPayment()->setCode($this->getPaymentCode() . '.' . TransactionType::RESERVATION);
        $this->getRequest()->getIdentification()->setReferenceId($paymentReferenceId);
        $this->prepareRequest();

        return $this;
    }
}
