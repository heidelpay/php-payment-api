<?php

namespace Heidelpay\Tests\PhpApi\Unit\PaymentMethods;

use Heidelpay\PhpApi\Response;
use PHPUnit\Framework\TestCase;
use Heidelpay\PhpApi\PaymentMethods\EPSPaymentMethod as EPS;

/**
 * EPS Test
 *
 * Connection tests can fail due to network issues and scheduled down times.
 * This does not have to mean that your integration is broken. Please verify the given debug information
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author  Ronja Wann
 *
 * @package  Heidelpay
 * @subpackage PhpApi
 * @category UnitTest
 */
class EPSPaymentMethodTest extends TestCase
{
    /**
     * SecuritySender
     *
     * @var string SecuritySender
     */
    protected $SecuritySender = '31HA07BC8124AD82A9E96D9A35FAFD2A';
    /**
     * UserLogin
     *
     * @var string UserLogin
     */
    protected $UserLogin = '31ha07bc8124ad82a9e96d486d19edaa';
    /**
     * UserPassword
     *
     * @var string UserPassword
     */
    protected $UserPassword = 'password';
    /**
     * TransactionChannel
     *
     * @var string TransactionChannel
     */
    protected $TransactionChannel = '31HA07BC812125981B4F52033DE486AB';
    /**
     * SandboxRequest
     *
     * Request will be send to Heidelpay sandbox payment system.
     *
     * @var string
     */
    protected $SandboxRequest = true;

    protected static $customerDetails = array(
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
     * @var \Heidelpay\PhpApi\PaymentMethods\EPSPaymentMethod
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
     * Set up function will create a EPS object for each test case
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
        $EPS = new EPS();

        $EPS->getRequest()->authentification($this->SecuritySender, $this->UserLogin, $this->UserPassword,
            $this->TransactionChannel, 'TRUE');

        $EPS->getRequest()->customerAddress(...static::$customerDetails);

        $EPS->_dryRun = true;

        $this->paymentObject = $EPS;
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
     * Test case for a single EPS authorize
     *
     * @return string payment reference id for the EPS authorize transaction
     * @group connectionTest
     */
    public function testAuthorize()
    {
        $timestamp = $this->getMethod(__METHOD__) . ' ' . date('Y-m-d H:i:s');
        $this->paymentObject->getRequest()->basketData($timestamp, 23.12, $this->currency, $this->secret);
        $this->paymentObject->getRequest()->async('DE', 'https://dev.heidelpay.de');

        $this->paymentObject->authorize();

        /* prepare request and send it to payment api */
        $request = $this->paymentObject->getRequest()->convertToArray();
        /** @var Response $response */
        list(, $response) = $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);

        $this->assertTrue($response->isSuccess(), 'Transaction failed : ' . print_r($response, 1));
        $this->assertFalse($response->isError(), 'authorize failed : ' . print_r($response->getError(), 1));

        return (string)$response->getPaymentReferenceId();
    }
}
