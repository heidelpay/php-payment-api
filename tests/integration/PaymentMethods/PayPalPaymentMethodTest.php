<?php

namespace Heidelpay\Tests\PhpPaymentApi\Integration\PaymentMethods;

use Heidelpay\PhpPaymentApi\Response;
use Heidelpay\PhpPaymentApi\PaymentMethods\PayPalPaymentMethod as PayPal;
use Heidelpay\Tests\PhpPaymentApi\Helper\BasePaymentMethodTest;

/**
 * PayPal Test
 *
 * Connection tests can fail due to network issues and scheduled down times.
 * This does not have to mean that your integration is broken. Please verify the given debug information
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author  Jens Richter
 *
 * @package heidelpay\php-payment-api\tests\integration
 */
class PayPalPaymentMethodTest extends BasePaymentMethodTest
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
     * @var PayPal
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
     * Set up function will create a PayPal object for each test case
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    // @codingStandardsIgnoreStart
    public function _before()
    {
        // @codingStandardsIgnoreEnd
        $authentication = $this->authentication
            ->setTransactionChannel('31HA07BC8142C5A171749A60D979B6E4')
            ->getAuthenticationArray();
        $customerDetails = $this->customerData
            ->setCompanyName('DevHeidelpay')
            ->getCustomerDataArray();

        $PayPal = new PayPal();
        $PayPal->getRequest()->authentification(...$authentication);
        $PayPal->getRequest()->customerAddress(...$customerDetails);
        $PayPal->dryRun = true;

        $this->paymentObject = $PayPal;
    }

    /**
     * Test case for a PayPal registration
     *
     * @return string payment reference id for the PayPal registration transaction
     * @group connectionTest
     *
     * @throws \Exception
     * @test
     */
    public function registration()
    {
        $timestamp = $this->getMethod(__METHOD__) . ' ' . date('Y-m-d H:i:s');
        $this->paymentObject->getRequest()->basketData($timestamp, 23.12, $this->currency, $this->secret);
        $this->paymentObject->getRequest()->async('DE', 'https://dev.heidelpay.com');

        $this->paymentObject->registration();

        /* prepare request and send it to payment api */
        $request = $this->paymentObject->getRequest()->toArray();

        /** @var Response $response */
        list($result, $response) =
            $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);

        $this->assertTrue($response->isSuccess(), 'Transaction failed : ' . print_r($response, 1));
        $this->assertFalse($response->isError(), 'Transaction failed : ' . print_r($response->getError(), 1));

        $this->logDataToDebug($result);

        return (string)$response->getPaymentReferenceId();
    }

    /**
     * Test case for a PayPal reregistration
     *
     * @group connectionTest
     *
     * @param null $referenceId
     *
     * @throws \Exception
     * @depends registration
     *
     * @test
     */
    public function reregistration($referenceId = null)
    {
        $timestamp = $this->getMethod(__METHOD__) . ' ' . date('Y-m-d H:i:s');
        $this->paymentObject->getRequest()->basketData($timestamp, 23.12, $this->currency, $this->secret);
        $this->paymentObject->getRequest()->async('DE', 'https://dev.heidelpay.com');

        $this->paymentObject->reregistration($referenceId);

        /* prepare request and send it to payment api */
        $request = $this->paymentObject->getRequest()->toArray();

        /** @var Response $response */
        list($result, $response) =
            $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);

        $this->assertTrue($response->isSuccess(), 'Transaction failed : ' . print_r($response, 1));
        $this->assertFalse($response->isError(), 'Transaction failed : ' . print_r($response->getError(), 1));

        $this->logDataToDebug($result);
    }

    /**
     * Test case for a single PayPal authorisation
     *
     * @return string payment reference id for the PayPal authorize transaction
     * @group connectionTest
     *
     * @depends registration
     *
     * @throws \Exception
     * @test
     */
    public function authorize()
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

        $this->logDataToDebug($result);
    }

    /**
     * Test case for a single PayPal debit
     *
     * @return string payment reference id for the PayPal authorize transaction
     * @group connectionTest
     *
     * @depends registration
     *
     * @throws \Exception
     * @test
     */
    public function debit()
    {
        $timestamp = $this->getMethod(__METHOD__) . ' ' . date('Y-m-d H:i:s');
        $this->paymentObject->getRequest()->basketData($timestamp, 23.12, $this->currency, $this->secret);
        $this->paymentObject->getRequest()->async('DE', 'https://dev.heidelpay.com');

        $this->paymentObject->debit();

        /* prepare request and send it to payment api */
        $request = $this->paymentObject->getRequest()->toArray();

        /** @var Response $response */
        list($result, $response) =
            $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);

        $this->assertTrue($response->isSuccess(), 'Transaction failed : ' . print_r($response, 1));
        $this->assertFalse($response->isError(), 'debit failed : ' . print_r($response->getError(), 1));

        $this->logDataToDebug($result);
    }
}
