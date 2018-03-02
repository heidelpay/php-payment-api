<?php

namespace Heidelpay\PhpPaymentApi\TransactionTypes;

use Heidelpay\PhpPaymentApi\Constants\TransactionType;

/**
 * This payment type will be used to update account data inside the heidelpay
 * system. Pass the referenceId of the registration you want to update.
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author  Simon Gabriel
 *
 * @package  Heidelpay
 * @subpackage PhpPaymentApi
 * @category PhpPaymentApi
 *
 * @since Class available since Release 1.4.0
 */
trait ReregistrationTransactionType
{
    /**
     * This payment type is used to update account data inside the heidelpay system.
     * The passed reference id will identify the registration to update.
     *
     * @param $referenceId
     *
     * @return ReregistrationTransactionType
     *
     * @throws \Exception
     */
    public function reregistration($referenceId)
    {
        $this->getRequest()->getPayment()->setCode($this->paymentCode . '.' . TransactionType::REREGISTRATION);
        $this->getRequest()->getIdentification()->setReferenceId($referenceId);
        $this->prepareRequest();

        return $this;
    }
}
