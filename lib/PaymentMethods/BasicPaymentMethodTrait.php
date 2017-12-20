<?php

namespace Heidelpay\PhpPaymentApi\PaymentMethods;

use Heidelpay\PhpPaymentApi\Adapter\HttpAdapterInterface;
use Heidelpay\PhpPaymentApi\Constants\ApiConfig;
use Heidelpay\PhpPaymentApi\Constants\TransactionMode;
use Heidelpay\PhpPaymentApi\Exceptions\UndefinedTransactionModeException;
use Heidelpay\PhpPaymentApi\Request as HeidelpayRequest;

/**
 * This classe is the basic payment method trait
 *
 * It contains the main properties and functions for
 * every payment method
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author  Jens Richter
 *
 * @package heidelpay\php-payment-api\paymentmethods
 */
trait BasicPaymentMethodTrait
{
    /**
     * @var string Payment code for the corresponding payment method
     */
    protected $paymentCode;

    /**
     * @var string Brand for the corresponding payment method
     */
    protected $brand;

    /**
     * HTTP Adapter for payment connection
     *
     * @var HttpAdapterInterface
     */
    protected $adapter;

    /**
     * Heidelpay request object
     *
     * @var \Heidelpay\PhpPaymentApi\Request
     */
    protected $request;

    /**
     * Heidelpay request array
     *
     * @var array request
     */
    protected $requestArray;

    /**
     * Heidelpay response object
     *
     * @var \Heidelpay\PhpPaymentApi\Response
     */
    protected $response;

    /**
     * Heidelpay response array
     *
     * @var array response
     */
    protected $responseArray;

    /**
     * Dry run
     *
     * If set to true request will be generated but not send to payment api.
     * This is use full for testing.
     *
     * @var boolean dry run
     */
    public $dryRun = false;

    /**
     * Returns the payment code for the payment request.
     *
     * @return string
     */
    public function getPaymentCode()
    {
        return $this->paymentCode;
    }

    /**
     * Returns the brand for the payment method.
     *
     * @return string
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Return the name of the used class
     *
     * @return string class name
     */
    public static function getClassName()
    {
        return substr(strrchr(get_called_class(), '\\'), 1);
    }

    /**
     * Set a new payment request object
     *
     * @param \Heidelpay\PhpPaymentApi\Request $Request
     */
    public function setRequest(HeidelpayRequest $Request)
    {
        $this->request = $Request;
    }

    /**
     * Get payment request object
     *
     * @return \Heidelpay\PhpPaymentApi\Request
     */
    public function getRequest()
    {
        if ($this->request === null) {
            return $this->request = new HeidelpayRequest();
        }

        return $this->request;
    }

    /**
     * Get response object
     *
     * @return \Heidelpay\PhpPaymentApi\Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set a HTTP Adapter for payment communication
     *
     * @param HttpAdapterInterface $adapter
     */
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Get HTTP Adapter for payment communication
     *
     * @return HttpAdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * Get url of the used payment api
     *
     * @throws UndefinedTransactionModeException
     *
     * @return boolean|string url of the payment api
     */
    public function getPaymentUrl()
    {
        $mode = $this->getRequest()->getTransaction()->getMode();

        if ($mode === null) {
            throw new UndefinedTransactionModeException('Transaction mode is not set');
        }

        if ($mode === TransactionMode::LIVE) {
            return ApiConfig::LIVE_URL;
        }

        return ApiConfig::TEST_URL;
    }

    /**
     * This function prepares the request for heidelpay api
     *
     * It will add the used payment method  and the brand to the request. If
     * dry run is set the request will only be convert to an array.
     *
     * @throws UndefinedTransactionModeException
     */
    public function prepareRequest()
    {
        $this->getRequest()->getCriterion()->set('payment_method', $this->getClassName());
        if ($this->brand !== null) {
            $this->getRequest()->getAccount()->setBrand($this->brand);
        }

        $uri = $this->getPaymentUrl();
        $this->requestArray = $this->getRequest()->convertToArray();

        if ($this->dryRun === false && $uri !== null && is_array($this->requestArray)) {
            list($this->responseArray, $this->response) =
                $this->getRequest()->send($uri, $this->requestArray, $this->getAdapter());
        }
    }

    /**
     * Returns an array for a json representation.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $return = [];
        foreach (get_object_vars($this) as $field => $value) {
            $return[$field] = $value;
        }

        return $return;
    }

    /**
     * @inheritdoc
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }
}
