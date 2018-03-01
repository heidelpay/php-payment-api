<?php

namespace Heidelpay\PhpPaymentApi\PaymentMethods;

use Heidelpay\PhpPaymentApi\Request;
use JsonSerializable;

/**
 * Interface for payment methods
 *
 * @license Use of this software requires acceptance of the License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link http://dev.heidelpay.com/heidelpay-php-payment-api/
 *
 * @author Stephano Vogel
 *
 * @package heidelpay\php-payment-api\paymentmethods
 */
interface PaymentMethodInterface extends JsonSerializable
{
    /**
     * Returns the payment code for the payment request.
     *
     * @return string
     */
    public function getPaymentCode();

    /**
     * Returns the brand for the payment method.
     *
     * @return string
     */
    public function getBrand();

    /**
     * Get url of the used payment api
     *
     * @throws \Heidelpay\PhpPaymentApi\Exceptions\UndefinedTransactionModeException
     *
     * @return boolean|string url of the payment api
     */
    public function getPaymentUrl();

    /**
     * Get HTTP Adapter for payment communication
     *
     * @return \Heidelpay\PhpPaymentApi\Adapter\HttpAdapterInterface
     */
    public function getAdapter();

    /**
     * Returns the Request instance.
     *
     * @return \Heidelpay\PhpPaymentApi\Request
     */
    public function getRequest();

    /**
     * Returns the Response instance.
     *
     * @return \Heidelpay\PhpPaymentApi\Response
     */
    public function getResponse();

    /**
     * Set a HTTP Adapter for payment communication
     *
     * @param \Heidelpay\PhpPaymentApi\Adapter\HttpAdapterInterface $adapter
     */
    public function setAdapter($adapter);

    /**
     * Set a new payment request object
     *
     * @param \Heidelpay\PhpPaymentApi\Request $heidelpayRequest
     */
    public function setRequest(Request $heidelpayRequest);

    /**
     * Returns a Json representation of itself.
     *
     * @param int $options
     *
     * @return string
     */
    public function toJson($options = 0);
}
