<?php
namespace Heidelpay\PhpApi;

/**
 * Heidelpay request object
 *
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
class Request extends AbstractMethod
{
    /**
     * Constructor will generate all necessary sub objects
     */
    public function __construct()
    {
        $this->criterion        = $this->getCriterion();
        $this->frontend         = $this->getFrontend();
        $this->identification   = $this->getIdentification();
        $this->presentation     = $this->getPresentation();
        $this->request          = $this->getRequest();
        $this->security         = $this->getSecurity();
        $this->transaction      = $this->getTransaction();
        $this->user             = $this->getUser();
    }
    
    /**
     * Set all necessary authentication parameters for this request
     *
     * @param string $SecuritySender
     * @param string $UserLogin
     * @param string $UserPassword
     * @param string $TransactionChannel
     * @param bool   $SandboxRequest
     */
    public function authentification($SecuritySender=null, $UserLogin=null, $UserPassword=null, $TransactionChannel=null, $SandboxRequest=false)
    {
        $this->getSecurity()->set('sender', $SecuritySender);
        $this->getUser()->set('login', $UserLogin);
        $this->getUser()->set('pwd', $UserPassword);
        $this->getTransaction()->set('channel', $TransactionChannel);
        $this->getTransaction()->set('mode', "LIVE");
        
        if ($SandboxRequest) {
            $this->getTransaction()->set('mode', "CONNECTOR_TEST");
        }
        return  $this;
    }
    
    /**
     * Set all necessary parameter for a asynchronous request
     *
     * @param string $LanguageCode
     * @param string $ResponseUrl
     *
     * @return \Heidelpay\PhpApi\Request
     */
    public function async($LanguageCode="EN", $ResponseUrl=null)
    {
        $this->getFrontend()->set('language', $LanguageCode);
        
        if ($ResponseUrl !== null) {
            $this->getFrontend()->set('response_url', $ResponseUrl);
            $this->getFrontend()->set('enabled', 'TRUE');
        }
        return  $this;
    }
    
    /**
     * Set all necessary customer parameter for a request
     *
     * @param string $nameGiven
     * @param string $nameFamily
     * @param string $nameCompany
     * @param string $shopperId
     * @param string $addressStreet
     * @param string $addressState
     * @param string $addressZip
     * @param string $addressCity
     * @param string $addressCountry
     * @param string $contactMail
     *
     * @return \Heidelpay\PhpApi\Request
     */
    public function customerAddress($nameGiven=null, $nameFamily=null, $nameCompany=null, $shopperId=null, $addressStreet=null, $addressState=null, $addressZip=null, $addressCity=null, $addressCountry=nuill, $contactMail=null)
    {
        $this->getName()->set('given', $nameGiven);
        $this->getName()->set('family', $nameFamily);
        $this->getName()->set('company', $nameCompany);
        $this->getIdentification()->set('shopperid', $shopperId);
        $this->getAddress()->set('street', $addressStreet);
        $this->getAddress()->set('state', $addressState);
        $this->getAddress()->set('zip', $addressZip);
        $this->getAddress()->set('city', $addressCity);
        $this->getAddress()->set('country', $addressCountry);
        $this->getContact()->set('email', $contactMail);
        
        return  $this;
    }
    
    /**
     * Set all basket or order information
     *
     * @param string $shopIdentifier
     * @param string $amount
     * @param string $currency
     * @param string $secret
     *
     * @return \Heidelpay\PhpApi\Request
     */
    public function basketData($shopIdentifier=null, $amount=null, $currency=null, $secret=null)
    {
        $this->getIdentification()->set('transactionid', $shopIdentifier);
        $this->getPresentation()->set('amount', $amount);
        $this->getPresentation()->set('currency', $currency);
        if ($secret !== null and $shopIdentifier !== null) {
            $this->getCriterion()->setSecret($shopIdentifier, $secret);
        }
        
        return $this;
    }
    
    /**
     * Convert request object to post key value format
     *
     * @return array request
     */
    public function prepareRequest()
    {
        $array = array();
        $request = (array) get_object_vars($this);
        
        foreach ($request as $ParameterFirstName => $ParmaterValues) {
            if ($ParmaterValues === null) {
                continue;
            }
            
            foreach ((array)get_object_vars($ParmaterValues) as $ParameterLastName => $ParameterValue) {
                if ($ParameterValue === null) {
                    continue;
                }
                $array[strtoupper($ParameterFirstName.'.'.$ParameterLastName)] = $ParameterValue;
            }
        }
        return $array;
    }
    
    /**
     *
     * @param string $uri  payment api url
     * @param array  $post heidelpay request parameter
     * @param \Heidelpay\PhpApi\Adapter\\$adapter
     *
     * @return array|object response|\Heidelpay\PhpApi\Response
     */
    public function send($uri=null, $post=null, $adapter=null)
    {
        if (is_object($adapter)) {
            $client = new $adapter;
        } else {
            $client = new \Heidelpay\PhpApi\Adapter\CurlAdapter();
        }
        
        return $client->sendPost($uri, $post);
    }
}
