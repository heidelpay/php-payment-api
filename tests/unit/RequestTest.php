<?php

namespace Heidelpay\Tests\PhpPaymentApi\Unit;

use Codeception\TestCase\Test;
use Heidelpay\PhpPaymentApi\Request;
use Heidelpay\PhpPaymentApi\ParameterGroups\CriterionParameterGroup;

/**
 *
 *  This unit test will cover an error in the connection and an simple post request to the sandbox payment system.
 *  Please note that connection test can fail due to network issues and scheduled downtime.
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
 * @category UnitTest
 */
class RequestTest extends Test
{
    /**
     * Authentication test
     *
     * @desc This test will check if the authentication parameters are set.
     * @group integrationTest
     * @test
     */
    public function prepareAuthenticationData()
    {
        $Request = new Request();

        $SecuritySender = '31HA07BC8142C5A171745D00AD63D182'; //SecuritySender
        $UserLogin = '31ha07bc8142c5a171744e5aef11ffd3'; // UserLogin
        $UserPassword = '93167DE7';                         // UserPassword
        $TransactionChannel = '31HA07BC8142C5A171744F3D6D155865'; // TransactionChannel credit card without 3d secure
        $SandboxRequest = true;

        $Request->authentification($SecuritySender, $UserLogin, $UserPassword, $TransactionChannel, $SandboxRequest);

        $this->assertEquals($SecuritySender, $Request->getSecurity()->getSender());
        $this->assertEquals($UserLogin, $Request->getUser()->getLogin());
        $this->assertEquals($UserPassword, $Request->getUser()->getPassword());
        $this->assertEquals($TransactionChannel, $Request->getTransaction()->getChannel());
        $this->assertEquals('CONNECTOR_TEST', $Request->getTransaction()->getMode());
    }

    /**
     * Set parameter for asynchronous response
     *
     * @desc This test will check if the request can be set to async
     * @group integrationTest
     * @test
     */
    public function async()
    {
        $Request = new Request();

        $LanguageCode = 'DE';
        $ResponseUrl = 'https://dev.heidelpay.de/';

        $Request->async($LanguageCode, $ResponseUrl);

        $this->assertEquals($LanguageCode, $Request->getFrontend()->getLanguage());
        $this->assertEquals($ResponseUrl, $Request->getFrontend()->getResponseUrl());
        $this->assertEquals('TRUE', $Request->getFrontend()->getEnabled());
    }

    /**
     * Customer address test
     *
     * @desc This test will check if all customer parameters can be added to the request object
     * @group integrationTest
     * @test
     */
    public function customerAddress()
    {
        $Request = new Request();

        $nameGiven = 'Heidel';
        $nameFamily = 'Berger-Payment';
        $nameCompany = 'DevHeidelpay';
        $shopperId = '12344';
        $addressStreet = 'Vagerowstr. 18';
        $addressState = 'DE-BW';
        $addressZip = '69115';
        $addressCity = 'Heidelberg';
        $addressCountry = 'DE';
        $contactMail = 'development@heidelpay.de';

        $Request->customerAddress(
            $nameGiven,
            $nameFamily,
            $nameCompany,
            $shopperId,
            $addressStreet,
            $addressState,
            $addressZip,
            $addressCity,
            $addressCountry,
            $contactMail
        );

        $this->assertEquals($nameGiven, $Request->getName()->getGiven());
        $this->assertEquals($nameFamily, $Request->getName()->getFamily());
        $this->assertEquals($nameCompany, $Request->getName()->getCompany());
        $this->assertEquals($shopperId, $Request->getIdentification()->getShopperId());
        $this->assertEquals($addressStreet, $Request->getAddress()->getStreet());
        $this->assertEquals($addressState, $Request->getAddress()->getState());
        $this->assertEquals($addressZip, $Request->getAddress()->getZip());
        $this->assertEquals($addressCity, $Request->getAddress()->getCity());
        $this->assertEquals($addressCountry, $Request->getAddress()->getCountry());
    }

    /**
     * Basket data test
     *
     * @desc This test will check if all customer parameters can be added to the request object
     * @group integrationTest
     * @test
     */
    public function setBasketData()
    {
        $Request = new Request();

        $shopIdentifier = '2843294932';
        $amount = 23.12;
        $currency = 'EUR';
        $secret = '39542395235ßfsokkspreipsr';

        $Request->basketData($shopIdentifier, $amount, $currency, $secret);

        $this->assertEquals($shopIdentifier, $Request->getIdentification()->getTransactionId());
        $this->assertEquals($amount, $Request->getPresentation()->getAmount());
        $this->assertEquals($currency, $Request->getPresentation()->getCurrency());
        $this->assertEquals(hash('sha512', $shopIdentifier . $secret), $Request->getCriterion()->getSecretHash());
    }

    /**
     * Prepare request test
     *
     * @desc This test will convert the request object to post array format
     * @group integrationTest
     * @test
     */
    public function convertToArray()
    {
        $request = new Request();
        $criterion = new CriterionParameterGroup();

        $shopIdentifier = '2843294932';
        $amount = 23.12;
        $currency = 'EUR';
        $secret = '39542395235ßfsokkspreipsr';

        $request->basketData($shopIdentifier, $amount, $currency, $secret);

        $referenceVars = array(
            'CRITERION.SECRET' => '209022666cd4706e5f451067592b6be1aff4a913d5bb7f8249f7418ee25c91b318ebac66f41a6692539c8923adfdad6aae26138b1b3a7e37a197ab952be57876',
            'FRONTEND.ENABLED' => 'TRUE',
            'FRONTEND.MODE' => 'WHITELABEL',
            'IDENTIFICATION.TRANSACTIONID' => '2843294932',
            'PRESENTATION.AMOUNT' => 23.12,
            'PRESENTATION.CURRENCY' => 'EUR',
            'REQUEST.VERSION' => '1.0',
            'TRANSACTION.MODE' => 'CONNECTOR_TEST',
            'CRITERION.SDK_NAME' => $criterion->getSdkName(),
            'CRITERION.SDK_VERSION' => $criterion->getSdkVersion()
        );

        $this->assertEquals($referenceVars, $request->convertToArray());
    }

    /**
     * Basket parameter group getter test
     *
     * @test
     */
    public function getBasket()
    {
        $request = new Request();

        $request->getBasket();
        $value = '31HA07BC8129FBB819367B2205CD6FB4';
        $request->getBasket()->set('id', $value);
        $this->assertEquals($value, $request->getBasket()->getId());
    }

    /**
     * Request parameter group getter test
     *
     * @test
     */
    public function getRequest()
    {
        $request = new Request();

        $request->getRequest();
        $value = '1.2';
        $request->getRequest()->set('version', $value);

        $this->assertEquals($value, $request->getRequest()->getVersion());
    }

    /**
     * Test that checks if a set criterion property returns the expected value.
     *
     * @test
     */
    public function requestCriterionParameterGroupGetterShouldReturnSetValue()
    {
        $request = new Request();

        $fieldName = 'test_val';
        $value = 'Test Value';

        $request->getCriterion()->set($fieldName, $value);

        $this->assertEquals($value, $request->getCriterion()->get($fieldName));
    }
}
