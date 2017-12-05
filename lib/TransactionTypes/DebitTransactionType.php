<?php

namespace Heidelpay\PhpPaymentApi\TransactionTypes;

/**
 * Transaction type debit
 *
 * This payment type will charge the given account directly.
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
trait DebitTransactionType
{
    /**
     * Payment type debit
     *
     * This payment type will charge the given account directly.
     *
     * @return \Heidelpay\PhpPaymentApi\PaymentMethods\AbstractPaymentMethod
     */
    public function debit()
    {
        $this->getRequest()->getPayment()->set('code', $this->_paymentCode . ".DB");
        $this->prepareRequest();

        return $this;
    }
}
