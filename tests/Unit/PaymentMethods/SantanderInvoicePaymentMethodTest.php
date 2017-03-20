<?php

namespace Heidelpay\Tests\PhpApi\Unit\PaymentMethods;

use PHPUnit\Framework\TestCase;
use Heidelpay\PhpApi\PaymentMethods\SantanderInvoicePaymentMethod as Invoice;

/**
 *,
 *  Invoice B2C secured Test
 *
 *  Connection tests can fail due to network issues and scheduled downtime.
 *  This does not have to mean that your integration is broken. Please verify the given debug information
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
 * @category UnitTest
 */
class SantanderInvoicePaymentMethodTest extends TestCase
{

    /** authentification parameter for heidelpay api
     *
     * @var array authentification parameter for heidelpay api
     */
    protected $authentification = array(
        '31HA07BC8142C5A171745D00AD63D182', //SecuritySender
        '31ha07bc8142c5a171744e5aef11ffd3', //UserLogin
        '93167DE7', //UserPassword
        '31HA07BC81856CAD6D8E07858ACD6CFB', //TransactionChannel
        true //Sandbox mode
    );

    /** customer address
     *
     * @var array customer address
     */
    protected $customerDetails = array(
        'Heidel', //NameGiven
        'Berger-Payment', //NameFamily
        null, //NameCompany
        '1234', //IdentificationShopperId
        'Vagerowstr. 18', //AddressStreet
        'DE-BW', //AddressState
        '69115', //AddressZip
        'Heidelberg', //AddressCity
        'DE', //AddressCountry
        'development@heidelpay.de' //Costumer
    );

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
     * @var \Heidelpay\PhpApi\PaymentMethods\InvoiceB2CSecuredPaymentMethod
     */
    protected $paymentObject = null;

    /**
     * Constructor used to set timezone to utc
     */
    public function __construct()
    {
        date_default_timezone_set('UTC');
        parent::__construct();
    }

    /**
     * Set up function will create a invoice object for each testcase
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
        $Invoice = new Invoice();

        $Invoice->getRequest()->authentification(...$this->authentification);

        $Invoice->getRequest()->customerAddress(...$this->customerDetails);

        $this->paymentObject = $Invoice;
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
     * Test case for a single invoice authorisation
     *
     * @return string payment reference id for the invoice authorize transaction
     * @group connectionTest
     * @test
     */
    public function Authorize()
    {
        $timestamp = $this->getMethod(__METHOD__) . " " . date("Y-m-d H:i:s");
        $this->paymentObject->getRequest()->basketData($timestamp, 123.12, $this->currency, $this->secret);

        $this->paymentObject->getRequest()->b2cSecured('MRS', '1982-07-12');
        $this->paymentObject->getRequest()->async('DE', 'https://dev.heidelpay.de');
        $this->paymentObject->getRequest()->getFrontend()->set('enabled', 'FALSE');

        $this->paymentObject->authorize();

        /* verify response */
        $this->assertTrue($this->paymentObject->getResponse()->verifySecurityHash($this->secret, $timestamp));

        /* transaction result */
        $this->assertTrue($this->paymentObject->getResponse()->isSuccess(),
            'Transaction failed : ' . print_r($this->paymentObject->getResponse(), 1));
        $this->assertFalse($this->paymentObject->getResponse()->isPending(), 'authorize is pending');
        $this->assertFalse($this->paymentObject->getResponse()->isError(),
            'authorize failed : ' . print_r($this->paymentObject->getResponse()->getError(), 1));

        return $this->authorizeReference = (string)$this->paymentObject->getResponse()->getPaymentReferenceId();
    }

    /**
     * Test case for a invoice finalize of a existing authorisation
     *
     * @param $referenceId string payment reference id of the invoice authorisation
     *
     * @return string payment reference id for the prepayment reversal transaction
     * @depends Authorize
     * @group connectionTest
     * @test
     */
    public function Finalize($referenceId)
    {
        $timestamp = $this->getMethod(__METHOD__) . " " . date("Y-m-d H:i:s");
        $this->paymentObject->getRequest()->basketData($timestamp, 82.12, $this->currency, $this->secret);

        $this->paymentObject->finalize($referenceId);

        /* verify response */
        $this->assertTrue($this->paymentObject->getResponse()->verifySecurityHash($this->secret, $timestamp));

        /* transaction result */
        $this->assertTrue($this->paymentObject->getResponse()->isSuccess(),
            'Transaction failed : ' . print_r($this->paymentObject->getResponse(), 1));
        $this->assertFalse($this->paymentObject->getResponse()->isPending(), 'reversal is pending');
        $this->assertFalse($this->paymentObject->getResponse()->isError(),
            'reversal failed : ' . print_r($this->paymentObject->getResponse()->getError(), 1));

        return (string)$this->paymentObject->getResponse()->getPaymentReferenceId();
    }

    /**
     * Test case for a invoice reversal of a existing authorisation
     *

     *
     * @return string payment reference id for the prepayment reversal transaction
     * @depends Finalize
     * @group connectionTest
     * @test
     */
    public function Reversal()
    {
        $referenceId = $this->Authorize();
        $timestamp = $this->getMethod(__METHOD__) . " " . date("Y-m-d H:i:s");
        $this->paymentObject->getRequest()->basketData($timestamp, 23.54, $this->currency, $this->secret);

        /* the refund can not be processed because there will be no receipt automatically on the sandbox */

        $this->paymentObject->reversal((string)$referenceId);

        /* verify response */
        $this->assertTrue($this->paymentObject->getResponse()->verifySecurityHash($this->secret, $timestamp));

        /* transaction result */
        $this->assertTrue($this->paymentObject->getResponse()->isSuccess(),
            'Transaction failed : ' . print_r($this->paymentObject->getResponse(), 1));
        $this->assertFalse($this->paymentObject->getResponse()->isPending(), 'reversal is pending');
        $this->assertFalse($this->paymentObject->getResponse()->isError(),
            'reversal failed : ' . print_r($this->paymentObject->getResponse()->getError(), 1));

        return (string)$this->paymentObject->getResponse()->getPaymentReferenceId();
    }

    /**
     * Test case for invoice refund
     *
     * @param string $referenceId reference id of the invoice to refund
     *
     * @return string payment reference id of the invoice refund transaction
     * @depends Authorize
     * @test
     * @group connectionTest
     */
    public function Refund($referenceId = null)
    {
        $timestamp = $this->getMethod(__METHOD__) . " " . date("Y-m-d H:i:s");
        $this->paymentObject->getRequest()->basketData($timestamp, 23.54, $this->currency, $this->secret);

        /* the refund can not be processed because there will be no receipt automatically on the sandbox */
        $this->paymentObject->_dryRun = true;

        $this->paymentObject->refund((string)$referenceId);

        $this->assertEquals('IV.RF', $this->paymentObject->getRequest()->getPayment()->getCode());
        return true;
    }
}
