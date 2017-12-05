<?php

namespace Heidelpay\PhpPaymentApi\TransactionTypes;

/**
 * Transaction type authorize
 *
 * Depending on the payment method this type normally means that the amount
 * of the given account will only be authorized. In case of payment methods
 * like Sofort and Giropay (so called online payments) this type will be
 * used just to get the redirect to their systems.
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
trait AuthorizeTransactionType
{
    /**
     * Payment type authorisation
     *
     * Depending on the payment method this type normally means that the amount
     * of the given account will only be authorized. In case of payment methods
     * like Sofort and Giropay (so called online payments) this type will be
     * used just to get the redirect to their systems.
     *
     * @return \Heidelpay\PhpPaymentApi\PaymentMethods\AbstractPaymentMethod
     */
    public function authorize()
    {
        $this->getRequest()->getPayment()->set('code', $this->_paymentCode . ".PA");
        $this->prepareRequest();

        return $this;
    }
}
