<?php

namespace Heidelpay\PhpPaymentApi;

use Heidelpay\PhpPaymentApi\Exceptions\HashVerificationException;
use Heidelpay\PhpPaymentApi\Exceptions\PaymentFormUrlException;
use Heidelpay\PhpPaymentApi\ParameterGroups\ConnectorParameterGroup;
use Heidelpay\PhpPaymentApi\ParameterGroups\ProcessingParameterGroup;

/**
 * Heidelpay response object
 *
 * @license    Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright  Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link       http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author     Jens Richter
 *
 * @package    Heidelpay
 * @subpackage PhpPaymentApi
 * @category   PhpPaymentApi
 */
class Response extends AbstractMethod
{
    /**
     * ConnectorParameterGroup
     *
     * @var \Heidelpay\PhpPaymentApi\ParameterGroups\ConnectorParameterGroup
     */
    protected $connector = null;

    /**
     * ProcessingParameterGroup
     *
     * @var \Heidelpay\PhpPaymentApi\ParameterGroups\ProcessingParameterGroup
     */
    protected $processing = null;

    /**
     * The constructor will take a given response in post format and convert
     * it to a response object
     *
     * @param array $rawResponse
     */
    public function __construct($rawResponse = null)
    {
        if ($rawResponse !== null and is_array($rawResponse)) {
            $this->splitArray($rawResponse);
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
     */
    public function splitArray($rawResponse)
    {
        foreach ($rawResponse as $arrayKey => $arrayValue) {
            $responseGroup = explode('_', strtolower($arrayKey), 2);

            if (is_array($responseGroup)) {
                switch ($responseGroup[0]) {
                    case 'address':
                        $this->getAddress()->set($responseGroup[1], $arrayValue);
                        break;
                    case 'account':
                        $this->getAccount()->set($responseGroup[1], $arrayValue);
                        break;
                    case 'basket':
                        $this->getBasket()->set($responseGroup[1], $arrayValue);
                        break;
                    case 'criterion':
                        $this->getCriterion()->set($responseGroup[1], $arrayValue);
                        break;
                    case 'config':
                        $this->getConfig()->set($responseGroup[1], $arrayValue);
                        break;
                    case 'contact':
                        $this->getContact()->set($responseGroup[1], $arrayValue);
                        break;
                    case 'connector':
                        $this->getConnector()->set($responseGroup[1], $arrayValue);
                        break;
                    case "frontend":
                        $this->getFrontend()->set($responseGroup[1], $arrayValue);
                        break;
                    case "identification":
                        $this->getIdentification()->set($responseGroup[1], $arrayValue);
                        break;
                    case "name":
                        $this->getName()->set($responseGroup[1], $arrayValue);
                        break;
                    case "payment":
                        $this->getPayment()->set($responseGroup[1], $arrayValue);
                        break;
                    case "presentation":
                        $this->getPresentation()->set($responseGroup[1], $arrayValue);
                        break;
                    case "processing":
                        $this->getProcessing()->set($responseGroup[1], $arrayValue);
                        break;
                    case "request":
                        $this->getRequest()->set($responseGroup[1], $arrayValue);
                        break;
                    case "riskinformation":
                        $this->getRequest()->set($responseGroup[1], $arrayValue);
                        break;
                    case "security":
                        $this->getSecurity()->set($responseGroup[1], $arrayValue);
                        break;
                    case "transaction":
                        $this->getTransaction()->set($responseGroup[1], $arrayValue);
                        break;
                    case "user":
                        $this->getUser()->set($responseGroup[1], $arrayValue);
                        break;
                }
            }
        }
        return $this;
    }

    /**
     * Response was successfull
     *
     * @return boolean
     */
    public function isSuccess()
    {
        if ($this->getProcessing()->getResult() === 'ACK') {
            return true;
        }
        return false;
    }

    /**
     * Response is pending
     *
     * @return boolean
     */
    public function isPending()
    {
        if ($this->getProcessing()->getStatusCode() === '80') {
            return true;
        }
        return false;
    }

    /**
     * Response has an error
     *
     * @return boolean
     */
    public function isError()
    {
        if ($this->getProcessing()->getResult() === 'ACK') {
            return false;
        }
        return true;
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
            throw new PaymentFormUrlException('PaymentCode not set');
        }

        list($code, $type) = explode('.', $this->getPayment()->getCode());

        if (($code == 'CC' or $code == 'DC')
            and $this->getIdentification()->getReferenceId() === null
            and $this->getFrontend()->getPaymentFrameUrl() !== null
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
     * @throws \Exception
     *
     * @return boolean
     */
    public function verifySecurityHash($secret = null, $identificationTransactionId = null)
    {
        if ($secret === null or $identificationTransactionId === null) {
            throw new HashVerificationException('$secret or $identificationTransactionId undefined');
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

        if ($referenceHash === (string)$this->getCriterion()->getSecretHash()) {
            return true;
        }

        throw new HashVerificationException(
            'Hash does not match. This could be some kind of manipulation or misconfiguration!'
        );
    }
}
