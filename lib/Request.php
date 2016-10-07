<?php
namespace Heidelpay\PhpApi;
use \Heidelpay\PhpApi\AbstractMethod;

use \Zend\Http\Client;
use \Heidelpay\PhpApi\Response;
/**
 * Heidelpay request object
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
class Request extends AbstractMethod
{

    /**
     * Constructor will generate all necessary sub objects 
     */
    function __construct()
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
     * @param string $SecuritySender
     * @param string $UserLogin
     * @param string $UserPassword
     * @param string $TransactionChannel
     * @param bool $SandboxRequest  
     */
    
    public function authentification( $SecuritySender=NULL, $UserLogin=NULL, $UserPassword=NULL, $TransactionChannel=NULL, $SandboxRequest=FALSE)
    {
        $this->getSecurity()->set('sender',$SecuritySender);
        $this->getUser()->set('login',$UserLogin);
        $this->getUser()->set('pwd', $UserPassword);
        $this->getTransaction()->set('channel',$TransactionChannel);
        $this->getTransaction()->set('mode',"LIVE");
        
        if ($SandboxRequest) {
            $this->getTransaction()->set('mode',"CONNECTOR_TEST");
        }
        return  $this; 
        
    }
    
    /**
     * Set all necessary parameter for a asynchronous request 
     * @param string $LanguageCode
     * @param string $ResponseUrl
     * @return \Heidelpay\PhpApi\Request
     */
    
    public function async($LanguageCode="EN", $ResponseUrl=NULL) 
    {
    	$this->getFrontend()->set('language', $LanguageCode);
    	
    	if ($ResponseUrl !== NULL) {
    		$this->getFrontend()->set('response_url',$ResponseUrl);
    		$this->getFrontend()->set('enabled','TRUE');
    	}
    	return  $this;
    }
    
    /**
     * Set all necessary customer parameter for a request
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
     * @return \Heidelpay\PhpApi\Request
     */
    public function customerAddress($nameGiven=NULL, $nameFamily=NULL, $nameCompany=NULL, $shopperId=NULL, $addressStreet=NULL, $addressState=NULL, $addressZip=NULL, $addressCity=NULL, $addressCountry="NULL", $contactMail=NULL)
    {
    	$this->getName()->set('given',$nameGiven);
    	$this->getName()->set('family',$nameFamily);
    	$this->getName()->set('company',$nameCompany);
    	$this->getIdentification()->set('shopperid',$shopperId);
    	$this->getAddress()->set('street',$addressStreet);
    	$this->getAddress()->set('state',$addressState);
    	$this->getAddress()->set('zip',$addressZip);
    	$this->getAddress()->set('city', $addressCity);
    	$this->getAddress()->set('country',$addressCountry);
    	$this->getContact()->set('email', $contactMail);
    	
    	return  $this;
    }
    
    /**
     * Set all basket or order information
     * @param string $shopIdentifier
     * @param string $amount
     * @param string $currency
     * @param string $secret
     * @return \Heidelpay\PhpApi\Request
     */
    public function basketData($shopIdentifier=NULL, $amount=NULL, $currency=NULL, $secret=NULL)
    {
    	$this->getIdentification()->set('transactionid',$shopIdentifier);
    	$this->getPresentation()->set('amount',$amount);
    	$this->getPresentation()->set('currency', $currency);
    	if ($secret !== NULL and $shopIdentifier !== NULL) $this->getCriterion()->setSecret($shopIdentifier, $secret);
    	
    	return $this;
    	
    }
    
    /**
     * Convert request object to post key value format
     * @return array request 
     */
	public function prepareRequest() {
		
		$array = array();
		$request = (array) get_object_vars($this);
		
		foreach ($request AS $ParameterFirstName => $ParmaterValues ) {
			if ($ParmaterValues === NULL) continue;
			
			foreach ((array)get_object_vars($ParmaterValues) AS $ParameterLastName => $ParameterValue) {
				if ($ParameterValue === NULL) continue;
				$array[strtoupper($ParameterFirstName.'.'.$ParameterLastName)] = $ParameterValue;
			}
			
		}
		return $array;
	}
	
	/**
	 * 
	 * @param string $uri payment api url
	 * @param array $post heidelpay request parameter
	 * @param \Heidelpay\PhpApi\Adapter\\$adapter
	 * @return array|object response|\Heidelpay\PhpApi\Response
	 */
    
    public function send($uri=NULL, $post=NULL, $adapter=NULL) 
    {
        if (is_object($adapter)) {
            $client = new $adapter ;
        } else {
            $client = new \Heidelpay\PhpApi\Adapter\CurlAdapter();
        }
    	
        return $client->sendPost($uri, $post);
    }
                                       
}