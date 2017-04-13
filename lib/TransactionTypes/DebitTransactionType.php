<?php

namespace Heidelpay\PhpApi\TransactionTypes;

/**
 * Transaction type debit
 *
 * This payment type will charge the given account directly.
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
trait DebitTransactionType
{
    /**
     * Payment type debit
     *
     * This payment type will charge the given account directly.
     *
     * @return \Heidelpay\PhpApi\PaymentMethods\AbstractPaymentMethod
     */
    public function debit()
    {
        $this->getRequest()->getPayment()->set('code', $this->_paymentCode . ".DB");
        $this->prepareRequest();

        return $this;
    }
}
