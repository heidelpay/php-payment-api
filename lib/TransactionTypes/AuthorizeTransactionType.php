<?php
namespace Heidelpay\PhpApi\TransactionTypes;

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
 * @link  https://dev.heidelpay.de/PhpApi
 *
 * @author  Jens Richter
 *
 * @package  Heidelpay
 * @subpackage PhpApi
 * @category PhpApi
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
     * @return \Heidelpay\PhpApi\PaymentMethodes\AbstractPaymentMethod|boolean
     */
    public function authorize()
    {
        $this->getRequest()->getPayment()->set('code', $this->_paymentCode.".PA");
        $this->getRequest()->getCriterion()->set('payment_method', $this->getClassName());
        if ($this->_brand !== null) {
            $this->getRequest()->getAccount()->set('brand', $this->_brand);
        }

        $uri = $this->getPaymentUrl();
        $this->_requestArray = $this->getRequest()->prepareRequest();

        if ($this->_dryRun === false and $uri !== null and is_array($this->_requestArray)) {
            list($this->_responseArray, $this->_response) = $this->getRequest()->send($uri, $this->_requestArray, $this->getAdapter());
        }

        return $this;
    }
}
