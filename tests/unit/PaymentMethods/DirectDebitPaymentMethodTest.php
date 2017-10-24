<?php

namespace Heidelpay\Tests\PhpApi\Unit\PaymentMethods;

use Heidelpay\PhpApi\Response;
use Codeception\TestCase\Test;
use Heidelpay\PhpApi\PaymentMethods\DirectDebitPaymentMethod as DirectDebit;
use Heidelpay\PhpApi\Adapter\CurlAdapter;

/**
 * Direct debit Test
 *
 * Connection tests can fail due to network issues and scheduled downtime.
 * This does not have to mean that your integration is broken. Please verify the given debug information
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author  Jens Richter
 *
 * @package  Heidelpay
 * @subpackage PhpApi
 * @category UnitTest
 */
class DirectDebitPaymentMethodTest extends Test
{
    /**
     * @var array authentication parameter for heidelpay api
     */
    protected static $authentication = array(
        '31HA07BC8142C5A171745D00AD63D182', //SecuritySender
        '31ha07bc8142c5a171744e5aef11ffd3', //UserLogin
        '93167DE7',                         //UserPassword
        '31HA07BC8142C5A171749A60D979B6E4', //TransactionChannel
        true                                //Sandbox mode
    );

    /**
     * @var array customer address
     */
    protected static $customerDetails = array(
        'Heidel',                   //NameGiven
        'Berger-Payment',           //NameFamily
        'DevHeidelpay',             //NameCompany
        '1234',                     //IdentificationShopperId
        'Vagerowstr. 18',           //AddressStreet
        'DE-BW',                    //AddressState
        '69115',                    //AddressZip
        'Heidelberg',               //AddressCity
        'DE',                       //AddressCountry
        'development@heidelpay.de'  //Customer
    );

    /**
     * payment account iban
     *
     * @var string $iban
     */
    protected $iban = 'DE89370400440532013000';

    /**
     * payment account holder
     *
     * @var string $holder
     */
    protected $holder = 'Heidel Berger-Payment';

    /**
     * Transaction currency
     *
     * @var string currency
     */
    protected $currency = 'EUR';

    /**
     * Secret
     *
     * The secret will be used to generate a hash using
     * transaction id + secret. This hash can be used to
     * verify the the payment response. Can be used for
     * brute force protection.
     *
     * @var string secret
     */
    protected $secret = 'Heidelpay-PhpApi';

    /**
     * PaymentObject
     *
     * @var DirectDebit
     */
    protected $paymentObject;

    /**
     * Constructor used to set timezone to utc
     */
    public function __construct()
    {
        date_default_timezone_set('UTC');

        parent::__construct();
    }

    /**
     * Set up function will create a direct debit object for each test case
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    // @codingStandardsIgnoreStart
    public function _before()
    {
        // @codingStandardsIgnoreEnd
        $DirectDebit = new DirectDebit();
        $DirectDebit->getRequest()->authentification(...self::$authentication);
        $DirectDebit->getRequest()->customerAddress(...self::$customerDetails);
        $DirectDebit->_dryRun = true;

        $this->paymentObject = $DirectDebit;
    }

    /**
     * Get current called method, without namespace
     *
     * @param string $method
     *
     * @return string class and method
     */
    public function getMethod($method)
    {
        return substr(strrchr($method, '\\'), 1);
    }

    /**
     * Test case for a single direct debit authorize
     *
     * @return string payment reference id for the direct debit transaction
     * @group connectionTest
     * @test
     */
    public function authorize()
    {
        $timestamp = $this->getMethod(__METHOD__) . ' ' . date('Y-m-d H:i:s');
        $this->paymentObject->getRequest()->basketData($timestamp, 23.12, $this->currency, $this->secret);
        $this->paymentObject->getRequest()->async('DE', 'https://dev.heidelpay.de');
        $this->paymentObject->getRequest()->getFrontend()->set('enabled', 'FALSE');

        $this->paymentObject->setAdapter(CurlAdapter::class);

        $this->paymentObject->getRequest()->getAccount()->set('iban', $this->iban);
        $this->paymentObject->getRequest()->getAccount()->set('holder', $this->holder);

        $this->paymentObject->_dryRun = false;

        $this->paymentObject->authorize();


        $this->assertTrue(
            $this->paymentObject->getResponse()->isSuccess(),
            'Transaction failed : ' . print_r($this->paymentObject->getResponse(), 1)
        );
        $this->assertFalse(
            $this->paymentObject->getResponse()->isError(),
            'authorize failed : ' . print_r($this->paymentObject->getResponse()->getError(), 1)
        );

        return (string)$this->paymentObject->getResponse()->getPaymentReferenceId();
    }

    /**
     * Capture Test
     *
     * @depends authorize
     * @test
     *
     * @param $referenceId string
     *
     * @return string
     */
    public function capture($referenceId = null)
    {
        $timestamp = $this->getMethod(__METHOD__) . ' ' . date('Y-m-d H:i:s');
        $this->paymentObject->getRequest()->basketData($timestamp, 13.12, $this->currency, $this->secret);

        $this->paymentObject->capture((string)$referenceId);

        /* prepare request and send it to payment api */
        $request = $this->paymentObject->getRequest()->convertToArray();
        /** @var Response $response */
        list(, $response) = $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);

        $this->assertTrue($response->isSuccess(), 'Transaction failed : ' . print_r($response->getError(), 1));
        $this->assertFalse($response->isPending(), 'capture is pending');
        $this->assertFalse($response->isError(), 'capture failed : ' . print_r($response->getError(), 1));

        return (string)$response->getPaymentReferenceId();
    }

    /**
     * Test case for a single direct debit debit
     *
     * @return string payment reference id for the direct debit transaction
     * @group connectionTest
     * @test
     */
    public function debit()
    {
        $timestamp = $this->getMethod(__METHOD__) . ' ' . date('Y-m-d H:i:s');
        $this->paymentObject->getRequest()->basketData($timestamp, 13.42, $this->currency, $this->secret);
        $this->paymentObject->getRequest()->async('DE', 'https://dev.heidelpay.de');
        $this->paymentObject->getRequest()->getFrontend()->set('enabled', 'FALSE');

        $this->paymentObject->getRequest()->getAccount()->set('iban', $this->iban);
        $this->paymentObject->getRequest()->getAccount()->set('holder', $this->holder);

        $this->paymentObject->debit();

        /* prepare request and send it to payment api */
        $request = $this->paymentObject->getRequest()->convertToArray();
        /** @var Response $response */
        list(, $response) = $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);

        $this->assertTrue($response->isSuccess(), 'Transaction failed : ' . print_r($response, 1));
        $this->assertFalse($response->isError(), 'authorize failed : ' . print_r($response->getError(), 1));

        return (string)$response->getPaymentReferenceId();
    }

    /**
     * Test case for direct debit refund
     *
     * @param string $referenceId reference id of the direct debit to refund
     *
     * @return string payment reference id of the direct debit refund transaction
     * @depends debit
     * @test
     * @group connectionTest
     */
    public function refund($referenceId = null)
    {
        $timestamp = $this->getMethod(__METHOD__) . ' ' . date('Y-m-d H:i:s');
        $this->paymentObject->getRequest()->basketData($timestamp, 3.54, $this->currency, $this->secret);

        $this->paymentObject->refund((string)$referenceId);

        /* prepare request and send it to payment api */
        $request = $this->paymentObject->getRequest()->convertToArray();
        /** @var Response $response */
        list(, $response) = $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);

        $this->assertTrue($response->isSuccess(), 'Transaction failed : ' . print_r($response->getError(), 1));
        $this->assertFalse($response->isPending(), 'authorize on registration is pending');
        $this->assertFalse(
            $response->isError(),
            'authorized on registration failed : ' . print_r($response->getError(), 1)
        );

        return (string)$response->getPaymentReferenceId();
    }

    /**
     * Test case for a single direct debit debit
     *
     * @return string payment reference id for the direct debit transaction
     * @group connectionTest
     * @test
     */
    public function registration()
    {
        $timestamp = $this->getMethod(__METHOD__) . ' ' . date('Y-m-d H:i:s');
        $this->paymentObject->getRequest()->basketData($timestamp, 13.42, $this->currency, $this->secret);
        $this->paymentObject->getRequest()->async('DE', 'https://dev.heidelpay.de');
        $this->paymentObject->getRequest()->getFrontend()->set('enabled', 'FALSE');

        $this->paymentObject->getRequest()->getAccount()->set('iban', $this->iban);
        $this->paymentObject->getRequest()->getAccount()->set('holder', $this->holder);

        $this->paymentObject->registration();

        /* prepare request and send it to payment api */
        $request = $this->paymentObject->getRequest()->convertToArray();
        /** @var Response $response */
        list(, $response) = $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);

        $this->assertTrue($response->isSuccess(), 'Transaction failed : ' . print_r($response, 1));
        $this->assertFalse($response->isError(), 'authorize failed : ' . print_r($response->getError(), 1));

        return (string)$response->getPaymentReferenceId();
    }

    /**
     * Test case for a direct debit reversal of a existing authorisation
     *
     * @param string $referenceId payment reference id of the direct debit authorisation
     *
     * @return string payment reference id for the credit card reversal transaction
     * @depends authorize
     * @test
     * @group connectionTest
     */
    public function reversal($referenceId = null)
    {
        $timestamp = $this->getMethod(__METHOD__) . ' ' . date('Y-m-d H:i:s');
        $this->paymentObject->getRequest()->basketData($timestamp, 2.12, $this->currency, $this->secret);

        $this->paymentObject->reversal((string)$referenceId);

        /* prepare request and send it to payment api */
        $request = $this->paymentObject->getRequest()->convertToArray();
        /** @var Response $response */
        list(, $response) = $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);

        $this->assertTrue($response->isSuccess(), 'Transaction failed : ' . print_r($response->getError(), 1));
        $this->assertFalse($response->isPending(), 'reversal is pending');
        $this->assertFalse($response->isError(), 'reversal failed : ' . print_r($response->getError(), 1));

        return (string)$response->getPaymentReferenceId();
    }

    /**
     * Test case for a direct debit rebill of an existing debit or capture
     *
     * @param $referenceId string payment reference id of the direct debit debit or capture
     *
     * @return string payment reference id for the direct debit rebill transaction
     * @depends debit
     * @test
     * @group connectionTest
     */
    public function rebill($referenceId = null)
    {
        $timestamp = $this->getMethod(__METHOD__) . ' ' . date('Y-m-d H:i:s');
        $this->paymentObject->getRequest()->basketData($timestamp, 2.12, $this->currency, $this->secret);

        $this->paymentObject->rebill((string)$referenceId);

        /* prepare request and send it to payment api */
        $request = $this->paymentObject->getRequest()->convertToArray();
        /** @var Response $response */
        list(, $response) = $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);

        $this->assertTrue($response->isSuccess(), 'Transaction failed : ' . print_r($response->getError(), 1));
        $this->assertFalse($response->isPending(), 'reversal is pending');
        $this->assertFalse($response->isError(), 'reversal failed : ' . print_r($response->getError(), 1));

        return (string)$response->getPaymentReferenceId();
    }

    /**
     * Test case for direct debit authorisation on a registration
     *
     * @param $referenceId string reference id of the direct debit registration
     *
     * @return string payment reference id of the direct debit authorisation
     * @depends registration
     * @test
     * @group  connectionTest
     */
    public function authorizeOnRegistration($referenceId = null)
    {
        $timestamp = $this->getMethod(__METHOD__) . ' ' . date('Y-m-d H:i:s');
        $this->paymentObject->getRequest()->basketData($timestamp, 23.12, $this->currency, $this->secret);

        $this->paymentObject->getRequest()->getFrontend()->set('enabled', 'FALSE');

        $this->paymentObject->authorizeOnRegistration((string)$referenceId);

        /* prepare request and send it to payment api */
        $request = $this->paymentObject->getRequest()->convertToArray();
        /** @var Response $response */
        list(, $response) = $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);

        $this->assertTrue($response->isSuccess(), 'Transaction failed : ' . print_r($response->getError(), 1));
        $this->assertFalse($response->isPending(), 'authorize on registration is pending');
        $this->assertFalse(
            $response->isError(),
            'authorized on registration failed : ' . print_r($response->getError(), 1)
        );

        return (string)$response->getPaymentReferenceId();
    }
}
