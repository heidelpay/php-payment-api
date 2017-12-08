<?php

namespace Heidelpay\PhpPaymentApi;

use Heidelpay\PhpPaymentApi\Adapter\CurlAdapter;
use Heidelpay\PhpPaymentApi\Adapter\HttpAdapterInterface;

/**
 * Heidelpay request object
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
class Request extends AbstractMethod
{
    /**
     * Constructor will generate all necessary sub objects
     */
    public function __construct()
    {
        $this->criterion = $this->getCriterion();
        $this->frontend = $this->getFrontend();
        $this->identification = $this->getIdentification();
        $this->presentation = $this->getPresentation();
        $this->request = $this->getRequest();
        $this->security = $this->getSecurity();
        $this->transaction = $this->getTransaction();
        $this->user = $this->getUser();
    }

    /**
     * Set all necessary authentication parameters for this request
     *
     * @param string $securitySender     security sender parameter f.e 31HA07BC8142C5A171745D00AD63D182
     * @param string $userLogin          user login parameter f.e. 31ha07bc8142c5a171744e5aef11ffd3
     * @param string $userPassword       user password f.e. 93167DE7
     * @param string $transactionChannel channel id of the payment method f.e. 31HA07BC8142C5A171744F3D6D155865
     * @param bool   $sandboxRequest     choose between sandbox and productive payment system
     *
     * @return \Heidelpay\PhpPaymentApi\Request
     */
    public function authentification(
        $securitySender = null,
        $userLogin = null,
        $userPassword = null,
        $transactionChannel = null,
        $sandboxRequest = false
    ) {
        $this->getSecurity()->setSender($securitySender);
        $this->getUser()->setLogin($userLogin);
        $this->getUser()->setPassword($userPassword);
        $this->getTransaction()->setChannel($transactionChannel);
        $this->getTransaction()->setMode("LIVE");

        if ($sandboxRequest) {
            $this->getTransaction()->setMode("CONNECTOR_TEST");
        }
        return $this;
    }

    /**
     * Set all necessary parameter for a asynchronous request
     *
     * @param string $languageCode language code 2 letters for error messages and iframe f.e EN
     * @param string $responseUrl  response url of your application f.e https://www.url.com/response.php
     *
     * @return \Heidelpay\PhpPaymentApi\Request
     */
    public function async($languageCode = "EN", $responseUrl = null)
    {
        $this->getFrontend()->setLanguage($languageCode);

        if ($responseUrl !== null) {
            $this->getFrontend()->setResponseUrl($responseUrl);
            $this->getFrontend()->setEnabled('TRUE');
        }
        return $this;
    }

    /**
     * Set all necessary customer parameter for a request
     *
     * @param string $nameGiven      customer given name f.e. John
     * @param string $nameFamily     customer family name f.e. Doe
     * @param string $nameCompany    company name f.e. Heidelpay
     * @param string $shopperId      customer id in your application f.e. 1249
     * @param string $addressStreet  address street of the customer f.e. Vagerowstr.
     * @param string $addressState   address state ot the customer f.e Bayern
     * @param string $addressZip     address zip code f.e. 69115
     * @param string $addressCity    address city f.e. Heidelberg
     * @param string $addressCountry address country code 2 letters f.e. DE
     * @param string $contactMail    email adress of the customer f.e. ab@mail.de
     *
     * @return \Heidelpay\PhpPaymentApi\Request
     */
    public function customerAddress(
        $nameGiven = null,
        $nameFamily = null,
        $nameCompany = null,
        $shopperId = null,
        $addressStreet = null,
        $addressState = null,
        $addressZip = null,
        $addressCity = null,
        $addressCountry = null,
        $contactMail = null
    ) {
        $this->getName()->setGiven($nameGiven);
        $this->getName()->setFamily($nameFamily);
        $this->getName()->setCompany($nameCompany);
        $this->getIdentification()->setShopperid($shopperId);
        $this->getAddress()->setStreet($addressStreet);
        $this->getAddress()->setState($addressState);
        $this->getAddress()->setZip($addressZip);
        $this->getAddress()->setCity($addressCity);
        $this->getAddress()->setCountry($addressCountry);
        $this->getContact()->setEmail($contactMail);

        return $this;
    }

    /**
     * Set all basket or order information
     *
     * @param string $shopIdentifier id of your application f.e. order-125454
     * @param string $amount         amount of the current basket f.e. 20.12
     * @param string $currency       currency code 3 letters f.e. USD
     * @param string $secret         a secret to prevent your application against fake responses
     *
     * @return \Heidelpay\PhpPaymentApi\Request
     */
    public function basketData($shopIdentifier = null, $amount = null, $currency = null, $secret = null)
    {
        $this->getIdentification()->setTransactionid($shopIdentifier);
        $this->getPresentation()->setAmount($amount);
        $this->getPresentation()->setCurrency($currency);
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
    public function convertToArray()
    {
        $array = array();
        $request = (array)get_object_vars($this);

        foreach ($request as $parameterFirstName => $parmaterValues) {
            if ($parmaterValues === null) {
                continue;
            }

            foreach ((array)get_object_vars($parmaterValues) as $parameterLastName => $parameterValue) {
                if ($parameterValue === null) {
                    continue;
                }
                $array[strtoupper($parameterFirstName . '.' . $parameterLastName)] = $parameterValue;
            }
        }
        return $array;
    }

    /**
     * Send request to payment api
     *
     * @param string                    $uri     payment api url
     * @param array                     $post    heidelpay request parameter
     * @param HttpAdapterInterface|null $adapter
     *
     * @return array response|\Heidelpay\PhpPaymentApi\Response
     */
    public function send($uri = null, $post = null, $adapter = null)
    {
        $client = $adapter;

        if (!$client instanceof HttpAdapterInterface) {
            $client = new CurlAdapter();
        }

        return $client->sendPost($uri, $post);
    }

    /**
     * Parameter used in case of b2c secured invoice or direct debit
     *
     * @param null $salutation customer salutation MR/MRS (Mandatory)
     * @param null $birthdate  customer birth date YYYY-MM-DD (Mandatory)
     * @param null $basketId   id of a given basket using heidelpay basket api (Optional)
     *
     * @return $this \Heidelpay\PhpPaymentApi\Request
     */
    public function b2cSecured($salutation = null, $birthdate = null, $basketId = null)
    {
        $this->getName()->setSalutation($salutation);
        $this->getName()->setBirthdate($birthdate);
        $this->getBasket()->setId($basketId);

        return $this;
    }
}
