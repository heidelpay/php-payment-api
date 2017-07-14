<?php

namespace Heidelpay\Tests\PhpApi\Unit\PaymentMethods;

use Heidelpay\PhpApi\PaymentMethods\EasyCreditPaymentMethod;
use Heidelpay\PhpApi\Response;
use PHPUnit\Framework\TestCase;

/**
 * easyCredit Tests
 *
 * @license Use of this software requires acceptance of the License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link https://dev.heidelpay.de/php-api
 *
 * @author Stephano Vogel
 *
 * @package heidelpay/php-api/tests/unit/paymentmethods/easycredit
 */
class EasyCreditPaymentMethodTest extends TestCase
{
    /**
     * @var array authentification parameter for heidelpay api
     */
    protected $authentification = array(
        '31HA07BC8181E8CCFDAD64E8A4B3B766', //SecuritySender
        '31ha07bc8181e8ccfdad73fd513d2a53', //UserLogin
        '4B2D4BE3', //UserPassword
        '31HA07BC8179C95F6B59366492FD253D', //TransactionChannel
        true //Sandbox mode
    );

    /**
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
     * @var \Heidelpay\PhpApi\PaymentMethods\EasyCreditPaymentMethod
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
     * Set up function will create a invoice object for each testcase
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
        $easyCredit = new EasyCreditPaymentMethod();

        $easyCredit->getRequest()->authentification(...$this->authentification);
        $easyCredit->getRequest()->customerAddress(...$this->customerDetails);
        $easyCredit->getRequest()->b2cSecured('MR', '1970-01-01');
        $easyCredit->getRequest()->async('DE', 'https://dev.heidelpay.de');

        $easyCredit->getRequest()->getRiskInformation()->set('guestcheckout', false);
        $easyCredit->getRequest()->getRiskInformation()->set('since', '2013-01-01');
        $easyCredit->getRequest()->getRiskInformation()->set('ordercount', 3);

        $easyCredit->getRequest()->basketData(
            'heidelpayEasyCreditTest',
            500.98,
            $this->currency,
            $this->secret
        );

        $this->paymentObject = $easyCredit;
    }

    /**
     * @test
     */
    public function initialRequest()
    {
        $this->paymentObject->initialize();

        $response = $this->paymentObject->getResponse();

        $this->assertTrue($response->isSuccess(), 'Response is not successful.');

        // following fields are essential for easycredit, so they must not be null.
        $this->assertNotNull($response->getConfig()->optin_text, 'easyCredit Optin Text is null.');
        $this->assertNotNull($response->getFrontend()->getRedirectUrl(), 'RedirectUrl is null.');
    }
}
