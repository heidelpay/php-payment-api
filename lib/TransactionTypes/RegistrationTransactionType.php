<?php

namespace Heidelpay\PhpPaymentApi\TransactionTypes;

/**
 * Transaction type registration
 *
 * This payment type will be used to save account data inside the heidelpay
 * system. You will get back a payment reference id. This gives you a way
 * to charge this account later or even to make a recurring payment.
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
trait RegistrationTransactionType
{
    /**
     * Payment type registration
     *
     * This payment type will be used to save account data inside the heidelpay
     * system. You will get back a payment reference id. This gives you a way
     * to charge this account later or even to make a recurring payment.
     *
     * @return \Heidelpay\PhpPaymentApi\PaymentMethods\AbstractPaymentMethod
     */
    public function registration()
    {
        $this->getRequest()->getPayment()->set('code', $this->_paymentCode . ".RG");
        $this->prepareRequest();

        return $this;
    }
}
