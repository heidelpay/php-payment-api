<?php

namespace Heidelpay\Tests\PhpPaymentApi\Integration\PaymentMethods;

use Exception;
use Heidelpay\PhpPaymentApi\Response;
use Heidelpay\PhpPaymentApi\PaymentMethods\EPSPaymentMethod;
use Heidelpay\Tests\PhpPaymentApi\Helper\BasePaymentMethodTest;

/**
 * EPS Test
 *
 * Connection tests can fail due to network issues and scheduled down times.
 * This does not have to mean that your integration is broken. Please verify the given debug information
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author  Ronja Wann
 *
 * @package heidelpay\php-payment-api\tests\integration
 */
class EPSPaymentMethodTest extends BasePaymentMethodTest
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
     * @var EPSPaymentMethod
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
    // @codingStandardsIgnoreStart
    public function _before()
    {
        // @codingStandardsIgnoreEnd
        $authentication = $this->authentication
            ->setSecuritySender('31HA07BC8142C5A171745D00AD63D182')
            ->setUserLogin('31ha07bc8142c5a171744e5aef11ffd3')
            ->setUserPassword('93167DE7')
            ->setTransactionChannel('31HA07BC816492169CE30CFBBF83B1D5')
            ->getAuthenticationArray();
        $customerDetails = $this->customerData->getCustomerDataArray();

        $EPS = new EPSPaymentMethod();
        $EPS->getRequest()->authentification(...$authentication);
        $EPS->getRequest()->customerAddress(...$customerDetails);
        $EPS->dryRun = true;

        $this->paymentObject = $EPS;
    }

    /**
     * Test case for a single EPS authorize
     *
     * @return string payment reference id for the EPS authorize transaction
     * @group connectionTest
     *
     * @throws Exception
     */
    public function testAuthorize()
    {
        $timestamp = $this->getMethod(__METHOD__) . ' ' . date('Y-m-d H:i:s');
        $this->paymentObject->getRequest()->basketData($timestamp, 23.12, $this->currency, $this->secret);
        $this->paymentObject->getRequest()->async('DE', 'https://dev.heidelpay.com');

        $this->paymentObject->authorize();

        /* prepare request and send it to payment api */
        $request = $this->paymentObject->getRequest()->toArray();
        /** @var Response $response */
        list($result, $response) =
            $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);

        $this->assertTrue($response->isSuccess(), 'Transaction failed : ' . print_r($response, 1));
        $this->assertFalse($response->isError(), 'authorize failed : ' . print_r($response->getError(), 1));
        $this->assertEmpty($response->getProcessing()->getRedirect()->getUrl());

        $this->logDataToDebug($result);

        return (string)$response->getPaymentReferenceId();
    }

    /**
     * Test case for a single EPS authorize
     *
     * @return string payment reference id for the EPS authorize transaction
     * @group connectionTest
     *
     * @throws Exception
     */
    public function testAuthorizeFrontendDisabled()
    {
        $request = $this->paymentObject->getRequest();

        $timestamp = $this->getMethod(__METHOD__) . ' ' . date('Y-m-d H:i:s');
        $request->basketData($timestamp, 23.12, $this->currency, $this->secret);
        $request->getFrontend()->setResponseUrl('http://technik.heidelpay.de/jonas/responseAdvanced/response.php');

        $request->getAccount()->setCountry('AT');
        $request->getFrontend()->setEnabled('FALSE');

        $this->paymentObject->authorize();

        /* prepare request and send it to payment api */
        $requestArray = $request->toArray();
        /** @var Response $response */
        list($result, $response) = $request->send($this->paymentObject->getPaymentUrl(), $requestArray);

        $this->assertTrue($response->isSuccess(), 'Transaction failed : ' . print_r($response, 1));
        $this->assertFalse($response->isError(), 'authorize failed : ' . print_r($response->getError(), 1));
        $this->assertNotEmpty($response->getProcessing()->getRedirect()->url);
        // Unfortunately this can only be tested in live mode
        //$this->assertNotEmpty($response->getProcessing()->getRedirect()->getParameter());

        $this->logDataToDebug($result);

        return (string)$response->getPaymentReferenceId();
    }
}
