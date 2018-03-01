<?php

namespace Heidelpay\PhpPaymentApi;

use Heidelpay\PhpPaymentApi\Constants\PaymentMethod;
use Heidelpay\PhpPaymentApi\Constants\ProcessingResult;
use Heidelpay\PhpPaymentApi\Constants\StatusCode;
use Heidelpay\PhpPaymentApi\Exceptions\HashVerificationException;
use Heidelpay\PhpPaymentApi\Exceptions\PaymentFormUrlException;
use Heidelpay\PhpPaymentApi\ParameterGroups\ConnectorParameterGroup;
use Heidelpay\PhpPaymentApi\ParameterGroups\ProcessingParameterGroup;

/**
 * Heidelpay response object
 *
 * @license    Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright  Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link       http://dev.heidelpay.com/heidelpay-php-payment-api/
 *
 * @author     Jens Richter
 *
 * @package heidelpay\php-payment-api
 */
class Response extends AbstractMethod
{
    /**
     * ConnectorParameterGroup
     *
     * @var \Heidelpay\PhpPaymentApi\ParameterGroups\ConnectorParameterGroup
     */
    protected $connector;

    /**
     * ProcessingParameterGroup
     *
     * @var \Heidelpay\PhpPaymentApi\ParameterGroups\ProcessingParameterGroup
     */
    protected $processing;

    /**
     * The constructor will take a given response in post format and convert
     * it to a response object
     *
     * @param array $rawResponse
     */
    public function __construct($rawResponse = null)
    {
        if ($rawResponse !== null && is_array($rawResponse)) {
            $this->mapFromPost($rawResponse);
        }
    }

    /**
     * Processing getter
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\ProcessingParameterGroup
     */
    public function getProcessing()
    {
        if ($this->processing === null) {
            return $this->processing = new ProcessingParameterGroup();
        }

        return $this->processing;
    }

    /**
     * Connector getter
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\ConnectorParameterGroup
     */
    public function getConnector()
    {
        if ($this->connector === null) {
            return $this->connector = new ConnectorParameterGroup();
        }

        return $this->connector;
    }

    /**
     * Splits post array parameters and converts it to a response object
     *
     * @param array $rawResponse
     *
     * @return \Heidelpay\PhpPaymentApi\Response
     *
     * @deprecated 1.3.0 Response::fromPost should be used to create an instance with POST parameters.
     */
    public function splitArray($rawResponse)
    {
        $this->mapFromPost($rawResponse);
        return $this;
    }

    /**
     * Response was successfull
     *
     * @return boolean
     */
    public function isSuccess()
    {
        return $this->getProcessing()->getResult() === ProcessingResult::ACK;
    }

    /**
     * Response is pending
     *
     * @return boolean
     */
    public function isPending()
    {
        return $this->getProcessing()->getStatusCode() === StatusCode::WAITING;
    }

    /**
     * Response has an error
     *
     * @return boolean
     */
    public function isError()
    {
        return $this->getProcessing()->getResult() !== ProcessingResult::ACK;
    }

    /**
     * Get the error code and message
     *
     * @return array error code and message
     */
    public function getError()
    {
        return array(
            'code' => $this->getProcessing()->getReturnCode(),
            'message' => $this->getProcessing()->getReturn()
        );
    }

    /**
     * Get payment reference id or uniqe id
     *
     * @return string payment uniqe id
     */
    public function getPaymentReferenceId()
    {
        return $this->getIdentification()->getUniqueId();
    }

    /**
     * Payment from url
     *
     * Used to create the payment form. In case of credit/debit card it will
     * be the iframe url.
     *
     * @return string PaymentFormUrl
     *
     * @throws PaymentFormUrlException
     */
    public function getPaymentFormUrl()
    {
        /*
         * PaymentFrameUrl for credit and debitcard
         */
        $code = null;
        $type = null;

        if ($this->getPayment()->getCode() === null) {
            throw new PaymentFormUrlException('The PaymentCode is not set.');
        }

        list($code, $type) = explode('.', $this->getPayment()->getCode());

        if (($code === PaymentMethod::CREDIT_CARD || $code === PaymentMethod::DEBIT_CARD)
            && $this->getIdentification()->getReferenceId() === null
            && $this->getFrontend()->getPaymentFrameUrl() !== null
        ) {
            return $this->getFrontend()->getPaymentFrameUrl();
        }

        /*
         * Redirect url
         */

        if ($this->getFrontend()->getRedirectUrl() !== null) {
            return $this->getFrontend()->getRedirectUrl();
        }

        throw new PaymentFormUrlException('PaymentFromUrl is unset!');
    }

    /**
     * Verify that the response secret hash matches the one given by initial request
     *
     * A mismatch can be a indication, that someone tries to send fake payment
     * response to your system. Please verify the source of the response. If it
     * is a legal one, it can be some kind of misconfiguration.
     *
     * @param string $secret                      your application's secret hash
     * @param string $identificationTransactionId basket or order reference id
     *
     * @throws HashVerificationException
     *
     * @return boolean
     */
    public function verifySecurityHash($secret = null, $identificationTransactionId = null)
    {
        if ($secret === null || $identificationTransactionId === null) {
            throw new HashVerificationException(
                'verifySecurityHash() - $secret or $identificationTransactionId undefined. '
                . 'Do not call the Response script directly, it is meant to be called by the heidelpay system.'
            );
        }

        if ($this->getProcessing()->getResult() === null) {
            throw new HashVerificationException(
                'The response object seems to be empty or it is not a valid heidelpay response!'
            );
        }

        if ($this->getCriterion()->getSecretHash() === null) {
            throw new HashVerificationException(
                'Empty secret hash, this could be some kind of manipulation or misconfiguration!'
            );
        }

        $referenceHash = hash('sha512', $identificationTransactionId . $secret);

        if ($referenceHash === $this->getCriterion()->getSecretHash()) {
            return true;
        }

        throw new HashVerificationException(
            'Hashes do not match. This could be some kind of manipulation or misconfiguration!'
        );
    }
}
