<?php

namespace Heidelpay\PhpApi;

use Heidelpay\PhpApi\ParameterGroups\AccountParameterGroup;
use Heidelpay\PhpApi\ParameterGroups\AddressParameterGroup;
use Heidelpay\PhpApi\ParameterGroups\BasketParameterGroup;
use Heidelpay\PhpApi\ParameterGroups\ConfigParameterGroup;
use Heidelpay\PhpApi\ParameterGroups\ContactParameterGroup;
use Heidelpay\PhpApi\ParameterGroups\CriterionParameterGroup;
use Heidelpay\PhpApi\ParameterGroups\FrontendParameterGroup;
use Heidelpay\PhpApi\ParameterGroups\IdentificationParameterGroup;
use Heidelpay\PhpApi\ParameterGroups\NameParameterGroup;
use Heidelpay\PhpApi\ParameterGroups\PaymentParameterGroup;
use Heidelpay\PhpApi\ParameterGroups\PresentationParameterGroup;
use Heidelpay\PhpApi\ParameterGroups\RequestParameterGroup;
use Heidelpay\PhpApi\ParameterGroups\SecurityParameterGroup;
use Heidelpay\PhpApi\ParameterGroups\TransactionParameterGroup;
use Heidelpay\PhpApi\ParameterGroups\UserParameterGroup;
use Heidelpay\PhpApi\ParameterGroups\RiskInformationParameterGroup;

/**
 * Abstract request/response class
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
abstract class AbstractMethod implements MethodInterface
{
    /**
     * AccountParameterGroup
     *
     * @var \Heidelpay\PhpApi\ParameterGroups\AccountParameterGroup
     */
    protected $account = null;

    /**
     * AddressParameterGroup
     *
     * @var \Heidelpay\PhpApi\ParameterGroups\AddressParameterGroup
     */
    protected $address = null;

    /**
     * BasketParameterGroup
     *
     * @var \Heidelpay\PhpApi\ParameterGroups\BasketParameterGroup
     */
    protected $basket = null;

    /**
     * ConfigParameterGroup
     *
     * @var \Heidelpay\PhpApi\ParameterGroups\ConfigParameterGroup
     */
    protected $config = null;

    /**
     * ContactParameterGroup
     *
     * @var \Heidelpay\PhpApi\ParameterGroups\ContactParameterGroup
     */
    protected $contact = null;

    /**
     * CriterionParameterGroup
     *
     * @var \Heidelpay\PhpApi\ParameterGroups\CriterionParameterGroup
     */
    protected $criterion = null;

    /**
     * FrontendParameterGroup
     *
     * @var \Heidelpay\PhpApi\ParameterGroups\FrontendParameterGroup
     */
    protected $frontend = null;

    /**
     * IdentificationParameterGroup
     *
     * @var \Heidelpay\PhpApi\ParameterGroups\IdentificationParameterGroup
     */
    protected $identification = null;

    /**
     * NameParameterGroup
     *
     * @var \Heidelpay\PhpApi\ParameterGroups\NameParameterGroup
     */
    protected $name = null;

    /**
     * PaymentParameterGroup
     *
     * @var \Heidelpay\PhpApi\ParameterGroups\PaymentParameterGroup
     */
    protected $payment = null;

    /**
     * Post
     *
     * @var \Heidelpay\PhpApi\ParameterGroups\PostParameterGroup
     */
    protected $post = null;

    /**
     * PresentationParameterGroup
     *
     * @var \Heidelpay\PhpApi\ParameterGroups\PresentationParameterGroup
     */
    protected $presentation = null;


    /**
     * RequestParameterGroup
     *
     * @var \Heidelpay\PhpApi\ParameterGroups\RequestParameterGroup
     */
    protected $request = null;

    /**
     * RiskInformationParameterGroup
     *
     * @var \Heidelpay\PhpApi\ParameterGroups\RiskInformationParameterGroup
     */
    protected $riskinformation = null;

    /**
     * SecurityParameterGroup
     *
     * @var \Heidelpay\PhpApi\ParameterGroups\SecurityParameterGroup
     */
    protected $security = null;

    /**
     * ShopParameterGroup
     *
     * @var \Heidelpay\PhpApi\ParameterGroups\ShopParameterGroup
     */
    protected $shop = null;

    /**
     * ShopmoduleParameterGroup
     *
     * @var \Heidelpay\PhpApi\ParameterGroups\ShopmoduleParameterGroup
     */
    protected $shopmodule = null;

    /**
     * TransactionParameterGroup
     *
     * @var \Heidelpay\PhpApi\ParameterGroups\TransactionParameterGroup
     */
    protected $transaction = null;

    /**
     * UserParameterGroup
     *
     * @var \Heidelpay\PhpApi\ParameterGroups\UserParameterGroup
     */
    protected $user = null;

    /**
     * Account getter
     *
     * @return \Heidelpay\PhpApi\ParameterGroups\AccountParameterGroup
     */
    public function getAccount()
    {
        if ($this->account === null) {
            return $this->account = new AccountParameterGroup();
        }
        return $this->account;
    }

    /**
     * Address getter
     *
     * @return \Heidelpay\PhpApi\ParameterGroups\AddressParameterGroup
     */
    public function getAddress()
    {
        if ($this->address === null) {
            return $this->address = new AddressParameterGroup();
        }
        return $this->address;
    }

    /**
     * Basket getter
     *
     * @return \Heidelpay\PhpApi\ParameterGroups\BasketParameterGroup
     */
    public function getBasket()
    {
        if ($this->basket === null) {
            return $this->basket = new BasketParameterGroup();
        }
        return $this->basket;
    }

    /**
     * Config getter
     *
     * @return \Heidelpay\PhpApi\ParameterGroups\ConfigParameterGroup
     */
    public function getConfig()
    {
        if ($this->config === null) {
            return $this->config = new ConfigParameterGroup();
        }
        return $this->config;
    }

    /**
     * Contact getter
     *
     * @return \Heidelpay\PhpApi\ParameterGroups\ContactParameterGroup
     */
    public function getContact()
    {
        if ($this->contact === null) {
            return $this->contact = new ContactParameterGroup();
        }
        return $this->contact;
    }

    /**
     * Criterion getter
     *
     * @return \Heidelpay\PhpApi\ParameterGroups\CriterionParameterGroup
     */
    public function getCriterion()
    {
        if ($this->criterion === null) {
            return $this->criterion = new CriterionParameterGroup();
        }
        return $this->criterion;
    }

    /**
     * Frondend getter
     *
     * @return \Heidelpay\PhpApi\ParameterGroups\FrontendParameterGroup
     */
    public function getFrontend()
    {
        if ($this->frontend === null) {
            return $this->frontend = new FrontendParameterGroup();
        }
        return $this->frontend;
    }

    /**
     * Identification getter
     *
     * @return \Heidelpay\PhpApi\ParameterGroups\IdentificationParameterGroup
     */
    public function getIdentification()
    {
        if ($this->identification === null) {
            return $this->identification = new IdentificationParameterGroup();
        }
        return $this->identification;
    }

    /**
     * Name getter
     *
     * @return \Heidelpay\PhpApi\ParameterGroups\NameParameterGroup
     */
    public function getName()
    {
        if ($this->name === null) {
            return $this->name = new NameParameterGroup();
        }
        return $this->name;
    }

    /**
     * Payment getter
     *
     * @return \Heidelpay\PhpApi\ParameterGroups\PaymentParameterGroup
     */
    public function getPayment()
    {
        if ($this->payment === null) {
            return $this->payment = new PaymentParameterGroup();
        }
        return $this->payment;
    }

    /**
     * Presentation getter
     *
     * @return \Heidelpay\PhpApi\ParameterGroups\PresentationParameterGroup
     */
    public function getPresentation()
    {
        if ($this->presentation === null) {
            return $this->presentation = new PresentationParameterGroup();
        }
        return $this->presentation;
    }

    /**
     * Request getter
     *
     * @return \Heidelpay\PhpApi\ParameterGroups\RequestParameterGroup
     */
    public function getRequest()
    {
        if ($this->request === null) {
            return $this->request = new RequestParameterGroup();
        }
        return $this->request;
    }

    /**
     * RiskInformation getter
     *
     * @return \Heidelpay\PhpApi\ParameterGroups\RiskInformationParameterGroup
     */
    public function getRiskInformation()
    {
        if ($this->riskinformation === null) {
            return $this->riskinformation = new RiskInformationParameterGroup();
        }
        return $this->riskinformation;
    }

    /**
     * Security getter
     *
     * @return \Heidelpay\PhpApi\ParameterGroups\SecurityParameterGroup
     */
    public function getSecurity()
    {
        if ($this->security === null) {
            return $this->security = new SecurityParameterGroup();
        }
        return $this->security;
    }

    /**
     * Transaction getter
     *
     * @return \Heidelpay\PhpApi\ParameterGroups\TransactionParameterGroup
     */
    public function getTransaction()
    {
        if ($this->transaction === null) {
            return $this->transaction = new TransactionParameterGroup();
        }
        return $this->transaction;
    }

    /**
     * User getter
     *
     * @return \Heidelpay\PhpApi\ParameterGroups\UserParameterGroup
     */
    public function getUser()
    {
        if ($this->user === null) {
            return $this->user = new UserParameterGroup();
        }
        return $this->user;
    }

    /**
     * @inheritdoc
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        $return = [];
        foreach (get_object_vars($this) as $field => $value) {
            $return[$field] = $value;
        }

        return $return;
    }
}
