<?php
namespace Heidelpay\PhpApi;

/**
 * Abstract request/response class 
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



abstract class AbstractMethod {
    /* Post Parameter Group */
    
    /**
     * AccountParameterGroup
     * @var \Heidelpay\PhpApi\ParameterGroups\AccountParameterGroup
     */
    protected $account = NULL;
    
    /**
     * AddressParameterGroup
     * @var \Heidelpay\PhpApi\ParameterGroups\AddressParameterGroup
     */
    
    protected $address = NULL;
    
    /**
     * ContactParameterGroup
     * @var \Heidelpay\PhpApi\ParameterGroups\ContactParameterGroup
     */
    
    protected $contact = NULL;
    
    /**
     * CriterionParameterGroup
     * @var \Heidelpay\PhpApi\ParameterGroups\CriterionParameterGroup
     */
    
    protected $criterion = NULL;
    
    /**
     * FrontendParameterGroup
     * @var \Heidelpay\PhpApi\ParameterGroups\FrontendParameterGroup
     */
    
    protected $frontend = NULL;
    
    /**
     * IdentificationParameterGroup
     * @var \Heidelpay\PhpApi\ParameterGroups\IdentificationParameterGroup
     */
    
    protected $identification = NULL;
    
    /**
     * NameParameterGroup
     * @var \Heidelpay\PhpApi\ParameterGroups\NameParameterGroup
     */
    
    protected $name = NULL;
    
    /**
     * PaymentParameterGroup
     * @var \Heidelpay\PhpApi\ParameterGroups\PaymentParameterGroup
     */
    
    protected $payment = NULL;
    
    /**
     * Post
     * @var 
     */
    
    protected $post = NULL;
    
    /**
     * PresentationParameterGroup
     * @var \Heidelpay\PhpApi\ParameterGroups\PresentationParameterGroup
     */
    
    protected $presentation = NULL;
    
    /**
     * ProcessingParameterGroup
     * @var \Heidelpay\PhpApi\ParameterGroups\ProcessingParameterGroup
     */
    
    protected $processing = NULL;
    
    /**
     * RequestParameterGroup
     * @var \Heidelpay\PhpApi\ParameterGroups\RequestParameterGroup
     */
    
    protected $request = NULL;
    
    /**
     * SecurityParameterGroup
     * @var \Heidelpay\PhpApi\ParameterGroups\SecurityParameterGroup
     */
    
    protected $security = NULL;
    
    /**
     * ShopParameterGroup
     * @var \Heidelpay\PhpApi\ParameterGroups\ShopParameterGroup
     */
    
    protected $shop = Null;
    
    /**
     * ShopmoduleParameterGroup
     * @var \Heidelpay\PhpApi\ParameterGroups\ShopmoduleParameterGroup
     */
    
    
    protected $shopmodule = NULL;
    
    /**
     * TransactionParameterGroup
     * @var \Heidelpay\PhpApi\ParameterGroups\TransactionParameterGroup
     */
    
    protected $transaction = NULL;
    
    /**
     * UserParameterGroup
     * @var \Heidelpay\PhpApi\ParameterGroups\UserParameterGroup
     */
    
    protected $user = NULL;
    
    /**
     * Account getter
     * @return \Heidelpay\PhpApi\ParameterGroups\AccountParameterGroup
     */
    
    public function getAccount()
    {
        if ($this->account === NULL ) {
            return $this->account = new \Heidelpay\PhpApi\ParameterGroups\AccountParameterGroup;
        }
        return $this->account;
    }
    /**
     * Address getter
     * @return \Heidelpay\PhpApi\ParameterGroups\AddressParameterGroup
     */
    public function getAddress()
    {
        if ($this->address === NULL ) {
            return $this->address = new \Heidelpay\PhpApi\ParameterGroups\AddressParameterGroup;
        }
        return $this->address;
    }
    
    /**
     * Contact getter
     * @return \Heidelpay\PhpApi\ParameterGroups\ContactParameterGroup
     */
    public function getContact()
    {
        if ($this->contact === NULL ) {
            return $this->contact = new \Heidelpay\PhpApi\ParameterGroups\ContactParameterGroup;
        }
        return $this->contact;
    }
    
    /**
     * Criterion getter
     * @return \Heidelpay\PhpApi\ParameterGroups\CriterionParameterGroup
     */
    public function getCriterion()
    {
        if ($this->criterion === NULL ) {
            return $this->criterion = new \Heidelpay\PhpApi\ParameterGroups\CriterionParameterGroup;
        }
        return $this->criterion;
    }
    /**
     * Frondend getter
     * @return \Heidelpay\PhpApi\ParameterGroups\FrontendParameterGroup
     */
    public function getFrontend()
    {
        if ($this->frontend === NULL ) {
            return $this->frontend = new \Heidelpay\PhpApi\ParameterGroups\FrontendParameterGroup;
        }
        return $this->frontend;
    }
    /**
     * Identification getter
     * @return \Heidelpay\PhpApi\ParameterGroups\IdentificationParameterGroup
     */
    public function getIdentification()
    {
        if ($this->identification === NULL ) {
            return $this->identification = new \Heidelpay\PhpApi\ParameterGroups\IdentificationParameterGroup;
        }
        return $this->identification;
    }
    /**
     * Name getter
     * @return \Heidelpay\PhpApi\ParameterGroups\NameParameterGroup
     */
    public function getName()
    {
        if ($this->name === NULL ) {
            return $this->name = new \Heidelpay\PhpApi\ParameterGroups\NameParameterGroup;
        }
        return $this->name;
    }
    /**
     * Payment getter
     * @return \Heidelpay\PhpApi\ParameterGroups\PaymentParameterGroup
     */
    public function getPaymemt()
    {
        if ($this->payment === NULL ) {
            return $this->payment = new \Heidelpay\PhpApi\ParameterGroups\PaymentParameterGroup;
        }
        return $this->payment;
    }
    /**
     * Presentation getter
     * @return \Heidelpay\PhpApi\ParameterGroups\PresentationParameterGroup
     */
    public function getPresentation()
    {
        if ($this->presentation === NULL ) {
            return $this->presentation = new \Heidelpay\PhpApi\ParameterGroups\PresentationParameterGroup;
        }
        return $this->presentation;
    }
    /**
     * Processing getter
     * @return \Heidelpay\PhpApi\ParameterGroups\ProcessingParameterGroup
     */
    public function getProcessing(){
        if ($this->processing === NULL) {
            return $this->processing = new \Heidelpay\PhpApi\ParameterGroups\ProcessingParameterGroup();
        }
    
        return $this->processing;
    }
    /**
     * Request getter
     * @return \Heidelpay\PhpApi\ParameterGroups\RequestParameterGroup
     */
    public function getRequest()
    {
        if ($this->request === NULL ) {
            return $this->request = new \Heidelpay\PhpApi\ParameterGroups\RequestParameterGroup;
        }
        return $this->request;
    }
    /**
     * Security getter
     * @return \Heidelpay\PhpApi\ParameterGroups\SecurityParameterGroup
     */
    public function getSecurity()
    {
        if ($this->security === NULL ) {
            return $this->security = new \Heidelpay\PhpApi\ParameterGroups\SecurityParameterGroup;
        }
        return $this->security;
    }
    /**
     * Transaction getter
     * @return \Heidelpay\PhpApi\ParameterGroups\TransactionParameterGroup
     */
    public function getTransaction()
    {
        if ($this->transaction === NULL ) {
            return $this->transaction = new \Heidelpay\PhpApi\ParameterGroups\TransactionParameterGroup;
        }
        return $this->transaction;
    }
    /**
     * User getter
     * @return \Heidelpay\PhpApi\ParameterGroups\UserParameterGroup
     */
    public function getUser()
    {
        if ($this->user === NULL ) {
            return $this->user = new \Heidelpay\PhpApi\ParameterGroups\UserParameterGroup;
        }
        return $this->user;
    }
}
