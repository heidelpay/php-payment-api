<?php

namespace Heidelpay\PhpPaymentApi\TransactionTypes;

use Heidelpay\PhpPaymentApi\Constants\TransactionType;

/**
 * Transaction type reversal
 *
 * This payment type will be used to give an uncharged amount or even parts of
 * it back to the given account. This can be used to lower an amount on an
 * invoice for example.
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
trait ReversalTransactionType
{
    /**
     * Payment type reversal
     *
     * This payment type will be used to give an uncharged amount or even parts of
     * it back to the given account. This can be used to lower an amount on an
     * invoice for example.
     *
     * @param mixed $paymentReferenceId payment reference id (unique id of the authorisation)
     *
     * @return $this
     *
     * @throws \Heidelpay\PhpPaymentApi\Exceptions\UndefinedTransactionModeException
     */
    public function reversal($paymentReferenceId)
    {
        $this->getRequest()->getPayment()->setCode($this->getPaymentCode() . '.' . TransactionType::REVERSAL);
        $this->getRequest()->getFrontend()->setEnabled('FALSE');
        $this->getRequest()->getIdentification()->setReferenceId($paymentReferenceId);
        $this->prepareRequest();

        return $this;
    }
}
