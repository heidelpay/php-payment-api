<?php

namespace Heidelpay\PhpApi\TransactionTypes;

/**
 * Trait for the Initialize transaction type
 *
 * @license Use of this software requires acceptance of the License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link https://dev.heidelpay.de/php-api
 *
 * @author Stephano Vogel
 *
 * @package heidelpay/php-api/transactiontypes/initialize
 */
trait InitializeTransactionType
{
    /**
     * Initialize Payment Request
     * The initialize request is for payment methods like wallet and hire purchase.
     * It initializes the request for the certain payment.
     *
     * @return $this
     */
    public function initialize()
    {
        $this->getRequest()->getPayment()->set('code', $this->_paymentCode . ".IN");
        $this->prepareRequest();

        return $this;
    }
}
