<?php

namespace Heidelpay\Tests\PhpPaymentApi\Integration\PaymentMethods;

use Heidelpay\PhpPaymentApi\PaymentMethods\SantanderHirePurchasePaymentMethod;
use Heidelpay\Tests\PhpPaymentApi\Helper\BasePaymentMethodTest;

/**
 * Santander hire purchase Tests
 *
 * @license Use of this software requires acceptance of the License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author Simon Gabriel
 *
 * @package heidelpay\php-payment-api\tests\integration
 */
class SantanderHirePurchasePaymentMethodTest extends BasePaymentMethodTest
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
     * @var \Heidelpay\PhpPaymentApi\PaymentMethods\SantanderHirePurchasePaymentMethod
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
            ->setSecuritySender('31HA07BC8142C823FFA6831A1C7A39EF')
            ->setUserLogin('31ha07bc8142c823ffa666acb95d3b3f')
            ->setUserPassword('81C93566')
            ->setTransactionChannel('31HA07BC8142C823FFA60C952A9C414D')
            ->getAuthenticationArray();
        $customerDetails = $this->customerData->getCustomerDataArray();

        $santander = new SantanderHirePurchasePaymentMethod();

        $santander->getRequest()->authentification(...$authentication);
        $santander->getRequest()->customerAddress(...$customerDetails);
        $santander->getRequest()->b2cSecured('MR', '1970-01-01');
        $santander->getRequest()->async('DE', 'https://dev.heidelpay.com');

        $santander->getRequest()->getRiskInformation()->set('guestcheckout', false);
        $santander->getRequest()->getRiskInformation()->set('since', '2013-01-01');
        $santander->getRequest()->getRiskInformation()->set('ordercount', 3);

        $santander->getRequest()->basketData(
            'santanderHirePurchaseTest',
            500.98,
            $this->currency,
            $this->secret
        );

        $this->paymentObject = $santander;
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

        // following field is essential for santander hire purchase, so it must not be null.
        $this->assertNotNull($response->getFrontend()->getRedirectUrl(), 'RedirectUrl is null.');

        $this->logDataToDebug();
    }
}
