<?php

namespace Heidelpay\PhpPaymentApi\TransactionTypes;

use Heidelpay\PhpPaymentApi\Constants\TransactionType;

/**
 * Transaction type capture
 *
 * You can charge a given authorisation by capturing the transaction.
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
trait CaptureTransactionType
{
    /**
     * Payment type capture
     *
     * You can charge a given authorisation by capturing the transaction.
     *
     * @param string $paymentReferenceId (unique id of the authorisation)
     *
     * @return $this
     *
     * @throws \Heidelpay\PhpPaymentApi\Exceptions\UndefinedTransactionModeException
     */
    public function capture($paymentReferenceId)
    {
        $this->getRequest()->getPayment()->setCode($this->getPaymentCode() . '.' . TransactionType::CAPTURE);
        $this->getRequest()->getFrontend()->setEnabled('FALSE');
        $this->getRequest()->getIdentification()->setReferenceId($paymentReferenceId);
        $this->prepareRequest();

        return $this;
    }
}
