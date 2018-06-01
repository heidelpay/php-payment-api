<?php

namespace Heidelpay\Tests\PhpPaymentApi\Integration\PaymentMethods;

use Heidelpay\PhpPaymentApi\PaymentMethods\EasyCreditPaymentMethod;
use Heidelpay\Tests\PhpPaymentApi\Helper\BasePaymentMethodTest;

/**
 * easyCredit Tests
 *
 * @license Use of this software requires acceptance of the License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author Stephano Vogel
 *
 * @package heidelpay\php-payment-api\tests\integration
 */
class EasyCreditPaymentMethodTest extends BasePaymentMethodTest
{
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
    protected $secret = 'Heidelpay-PhpPaymentApi';

    /**
     * PaymentObject
     *
     * @var \Heidelpay\PhpPaymentApi\PaymentMethods\EasyCreditPaymentMethod
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
     * Set up function will create a invoice object for each test case
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     *
     * @throws \Exception
     */
    // @codingStandardsIgnoreStart
    public function _before()
    {
        // @codingStandardsIgnoreEnd
        $authentication = $this->authentication
            ->setSecuritySender('31HA07BC8181E8CCFDAD64E8A4B3B766')
            ->setUserLogin('31ha07bc8181e8ccfdad73fd513d2a53')
            ->setUserPassword('4B2D4BE3')
            ->setTransactionChannel('31HA07BC8179C95F6B59366492FD253D')
            ->getAuthenticationArray();
        $customerDetails = $this->customerData->getCustomerDataArray();

        $easyCredit = new EasyCreditPaymentMethod();

        $easyCredit->getRequest()->authentification(...$authentication);
        $easyCredit->getRequest()->customerAddress(...$customerDetails);
        $easyCredit->getRequest()->b2cSecured('MR', '1970-01-01');
        $easyCredit->getRequest()->async('DE', 'https://dev.heidelpay.com');

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
     *
     * @throws \Heidelpay\PhpPaymentApi\Exceptions\UndefinedTransactionModeException
     */
    public function initialRequest()
    {
        $this->paymentObject->initialize();

        $response = $this->paymentObject->getResponse();

        $this->assertTrue($response->isSuccess(), 'Response is not successful.');

        // following fields are essential for easy credit, so they must not be null.
        $this->assertNotNull($response->getConfig()->optin_text, 'easyCredit Optin Text is null.');
        $this->assertNotNull($response->getFrontend()->getRedirectUrl(), 'RedirectUrl is null.');

        $this->logDataToDebug();
    }
}
