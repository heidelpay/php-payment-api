<?php

namespace Heidelpay\PhpPaymentApi;

use Heidelpay\PhpPaymentApi\ParameterGroups\AccountParameterGroup;
use Heidelpay\PhpPaymentApi\ParameterGroups\AddressParameterGroup;
use Heidelpay\PhpPaymentApi\ParameterGroups\BasketParameterGroup;
use Heidelpay\PhpPaymentApi\ParameterGroups\ConfigParameterGroup;
use Heidelpay\PhpPaymentApi\ParameterGroups\ContactParameterGroup;
use Heidelpay\PhpPaymentApi\ParameterGroups\CriterionParameterGroup;
use Heidelpay\PhpPaymentApi\ParameterGroups\FrontendParameterGroup;
use Heidelpay\PhpPaymentApi\ParameterGroups\IdentificationParameterGroup;
use Heidelpay\PhpPaymentApi\ParameterGroups\NameParameterGroup;
use Heidelpay\PhpPaymentApi\ParameterGroups\PaymentParameterGroup;
use Heidelpay\PhpPaymentApi\ParameterGroups\PresentationParameterGroup;
use Heidelpay\PhpPaymentApi\ParameterGroups\RequestParameterGroup;
use Heidelpay\PhpPaymentApi\ParameterGroups\SecurityParameterGroup;
use Heidelpay\PhpPaymentApi\ParameterGroups\TransactionParameterGroup;
use Heidelpay\PhpPaymentApi\ParameterGroups\UserParameterGroup;
use Heidelpay\PhpPaymentApi\ParameterGroups\RiskInformationParameterGroup;

/**
 * Abstract request/response class
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author  Jens Richter
 *
 * @package  Heidelpay
 * @subpackage PhpPaymentApi
 * @category PhpPaymentApi
 */
abstract class AbstractMethod implements MethodInterface
{
    /**
     * AccountParameterGroup
     *
     * @var \Heidelpay\PhpPaymentApi\ParameterGroups\AccountParameterGroup
     */
    protected $account = null;

    /**
     * AddressParameterGroup
     *
     * @var \Heidelpay\PhpPaymentApi\ParameterGroups\AddressParameterGroup
     */
    protected $address = null;

    /**
     * BasketParameterGroup
     *
     * @var \Heidelpay\PhpPaymentApi\ParameterGroups\BasketParameterGroup
     */
    protected $basket = null;

    /**
     * ConfigParameterGroup
     *
     * @var \Heidelpay\PhpPaymentApi\ParameterGroups\ConfigParameterGroup
     */
    protected $config = null;

    /**
     * ContactParameterGroup
     *
     * @var \Heidelpay\PhpPaymentApi\ParameterGroups\ContactParameterGroup
     */
    protected $contact = null;

    /**
     * CriterionParameterGroup
     *
     * @var \Heidelpay\PhpPaymentApi\ParameterGroups\CriterionParameterGroup
     */
    protected $criterion = null;

    /**
     * FrontendParameterGroup
     *
     * @var \Heidelpay\PhpPaymentApi\ParameterGroups\FrontendParameterGroup
     */
    protected $frontend = null;

    /**
     * IdentificationParameterGroup
     *
     * @var \Heidelpay\PhpPaymentApi\ParameterGroups\IdentificationParameterGroup
     */
    protected $identification = null;

    /**
     * NameParameterGroup
     *
     * @var \Heidelpay\PhpPaymentApi\ParameterGroups\NameParameterGroup
     */
    protected $name = null;

    /**
     * PaymentParameterGroup
     *
     * @var \Heidelpay\PhpPaymentApi\ParameterGroups\PaymentParameterGroup
     */
    protected $payment = null;

    /**
     * PresentationParameterGroup
     *
     * @var \Heidelpay\PhpPaymentApi\ParameterGroups\PresentationParameterGroup
     */
    protected $presentation = null;


    /**
     * RequestParameterGroup
     *
     * @var \Heidelpay\PhpPaymentApi\ParameterGroups\RequestParameterGroup
     */
    protected $request = null;

    /**
     * RiskInformationParameterGroup
     *
     * @var \Heidelpay\PhpPaymentApi\ParameterGroups\RiskInformationParameterGroup
     */
    protected $riskinformation = null;

    /**
     * SecurityParameterGroup
     *
     * @var \Heidelpay\PhpPaymentApi\ParameterGroups\SecurityParameterGroup
     */
    protected $security = null;

    /**
     * TransactionParameterGroup
     *
     * @var \Heidelpay\PhpPaymentApi\ParameterGroups\TransactionParameterGroup
     */
    protected $transaction = null;

    /**
     * UserParameterGroup
     *
     * @var \Heidelpay\PhpPaymentApi\ParameterGroups\UserParameterGroup
     */
    protected $user = null;

    /**
     * Account getter
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\AccountParameterGroup
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
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\AddressParameterGroup
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
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\BasketParameterGroup
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
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\ConfigParameterGroup
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
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\ContactParameterGroup
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
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\CriterionParameterGroup
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
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\FrontendParameterGroup
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
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\IdentificationParameterGroup
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
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\NameParameterGroup
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
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\PaymentParameterGroup
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
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\PresentationParameterGroup
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
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\RequestParameterGroup
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
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\RiskInformationParameterGroup
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
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\SecurityParameterGroup
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
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\TransactionParameterGroup
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
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\UserParameterGroup
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
