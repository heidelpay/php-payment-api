<?php

namespace Heidelpay\PhpApi\TransactionTypes;

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
 * @link  https://dev.heidelpay.de/PhpApi
 *
 * @author  Jens Richter
 *
 * @package  Heidelpay
 * @subpackage PhpApi
 * @category PhpApi
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
     *
     * @return \Heidelpay\PhpApi\PaymentMethods\AbstractPaymentMethod|boolean
     */
    public function registration()
    {
        $this->getRequest()->getPayment()->set('code', $this->_paymentCode . ".RG");
        $this->prepareRequest();
        return $this;
    }
}
