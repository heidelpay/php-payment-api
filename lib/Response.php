<?php
namespace Heidelpay\PhpApi;
use \Heidelpay\PhpApi\AbstractMethod;
/**
 * Heidelpay response object
 *
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 * @link  https://dev.heidelpay.de/PhpApi
 * @author  Jens Richter
 *
 * @package  Heidelpay
 * @subpackage PhpApi
 * @category PhpApi
 */

class Response extends AbstractMethod
{
    /**
     * The constructor will take a given response in post format and convert
     * it to a response object
     * @param array $RawResponse
     */
    function __construct($RawResponse=NULL)
    {
        if ($RawResponse !== NULL and is_array($RawResponse)) $this->splitArray($RawResponse);
        
    }
    /**
     * Splits post array parameters and converts it to a response object
     * @param array $RawResponse
     * @return \Heidelpay\PhpApi\Response
     */
    public function splitArray($RawResponse)
    {
        foreach ($RawResponse as $ArrayKey => $ArrayValue) {
            $ResponseGroup = explode ('_', strtolower($ArrayKey), 2);
            
            if (is_array($ResponseGroup)) {
                 switch ($ResponseGroup[0]) {
                      case 'address':
                            $this->getAddress()->set($ResponseGroup[1], $ArrayValue);
                            break;
                      case 'config':
                            $this->getConfig()->set($ResponseGroup[1], $ArrayValue);
                            break;
                      case 'contact':
                            $this->getContact()->set($ResponseGroup[1], $ArrayValue);
                            break;
                      case 'criterion':
                            $this->getCriterion()->set($ResponseGroup[1], $ArrayValue);
                            break;
                      case "frontend":
                            $this->getFrontend()->set($ResponseGroup[1], $ArrayValue);
                            break;
                      case "identification":
                            $this->getIdentification()->set($ResponseGroup[1], $ArrayValue);
                            break;
                      case "name":
                            $this->getName()->set($ResponseGroup[1], $ArrayValue);
                            break;
                      case "payment":
                            $this->getPaymemt()->set($ResponseGroup[1], $ArrayValue);
                            break;
                      case "presentation":
                            $this->getPresentation()->set($ResponseGroup[1], $ArrayValue);
                            break;
                      case "processing":
                            $this->getProcessing()->set($ResponseGroup[1], $ArrayValue);
                            break;
                      case "request":
                            $this->getRequest()->set($ResponseGroup[1], $ArrayValue);
                            break;
                      case "security":
                            $this->getSecurity()->set($ResponseGroup[1], $ArrayValue);
                            break;
                      case "transaction":
                            $this->getTransaction()->set($ResponseGroup[1], $ArrayValue);
                            break;
                      case "user":
                            $this->getUser()->set($ResponseGroup[1], $ArrayValue);
                            break;
                 }
            }
            
        }
        return $this;
    }
    /**
     * Response was successfull
     * @return boolean
     */
    public function isSuccess() {
        if ($this->getProcessing()->getResult() === 'ACK') return TRUE;
        return FALSE;
    }
    
    /**
     * Response is pending
     * @return boolean
     */
    public function isPending() {
       if ($this->getProcessing()->getStatusCode() === '80') return TRUE;
       return false;
    }
    /**
     * Response has an error
     * @return boolean
     */
    public function isError() {
        if ($this->getProcessing()->getResult() === 'ACK') return FALSE;
        return TRUE;
    }
    /**
     * Get the error code and message
     * @return array error code and message
     */
    public function getError() {
       return array( 'code' => $this->getProcessing()->getReturnCode(),
                     'message' => $this->getProcessing()->getReturn()
       );
    }
    
    /**
     * Get payment reference id or uniqe id
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
     * @return string|boolean PaymentFormUrl
     */
    public function getPaymentFromUrl()
    {
        /*
         * 
         */
        if ($this->getFrontend()->getEnabled() == 'TRUE')
        {
                /*
                 * PaymentFrameUrl for credit and debitcard
                 */
                $code = $type = NULL;
                list($code,$type) = explode('.', $this->getPaymemt()->getCode());
                if (($code == 'CC' or $code == 'DC') and $this->getIdentification()->getReferenceId() === NULL and $this->getFrontend()->getPaymentFrameUrl() !== NULL) {
                    return $this->getFrontend()->getPaymentFrameUrl();
                }
                /*
                 * Redirect url
                 */
                if ($this->getFrontend()->getRedirectUrl() !== NULL) {
                    return $this->getFrontend()->getRedirectUrl();
                }
                
        }
                
        throw new \Exception('PaymentFromUrl is unset!');
    }
    /**
     * Verify that the response secret hash matches the one given by initial request
     * 
     *  A mismatch can be a indication, that someone tries to send fake payment 
     *  response to your system. Please verify the source of the response. If it 
     *  is a legal one, it can be some kind of misconfiguration.
     *  
     * 
     * @param string $secret of your application
     * @param unknown $identificationTransactionId basket or order reference id 
     * @throws \Exception 
     * @return boolean
     */
    
    public function verifySecurityHash($secret=NULL,$identificationTransactionId=NULL) {
        
        if ( $secret === NULL or $identificationTransactionId === NULL) {
            throw new \Exception('$secret or $identificationTransactionId undefined');
        }
        
        if ($this->getProcessing()->getResult() === NULL) {
            throw new \Exception('The response object seems to be empty or it is not a valid heidelpay response!');
        }
        
        if ( $this->getCriterion()->getSecretHash() === NULL) {
            throw new \Exception('Empty secret hash, this could be some kind of manipulation or misconfiguration!');
        }
        
        $referenceHash = hash('sha512',$identificationTransactionId.$secret);
        
        if ($referenceHash === (string)$this->getCriterion()->getSecretHash()) {
           return true;
        }
        
        throw new \Exception('Hash does not match. This could be some kind of manipulation or misconfiguration!');
        
    }
                                       
}