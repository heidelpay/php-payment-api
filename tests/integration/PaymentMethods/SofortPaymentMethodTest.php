<?php

namespace Heidelpay\Tests\PhpPaymentApi\Integration\PaymentMethods;

use Heidelpay\PhpPaymentApi\Response;
use Heidelpay\PhpPaymentApi\PaymentMethods\SofortPaymentMethod as Sofort;
use Heidelpay\Tests\PhpPaymentApi\Helper\BasePaymentMethodTest;

/**
 * Sofort Test
 *
 * Connection tests can fail due to network issues and scheduled down times.
 * This does not have to mean that your integration is broken. Please verify the given debug information
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author  Jens Richter
 *
 * @package heidelpay\php-payment-api\tests\integration
 */
class SofortPaymentMethodTest extends BasePaymentMethodTest
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
     * @var \Heidelpay\PhpPaymentApi\PaymentMethods\SofortPaymentMethod
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
     * Set up function will create a sofort object for each test case
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    // @codingStandardsIgnoreStart
    public function _before()
    {
        // @codingStandardsIgnoreEnd
        $authentication = $this->authentication
            ->setTransactionChannel('31HA07BC8142C5A171749CDAA43365D2')
            ->getAuthenticationArray();
        $customerDetails = $this->customerData
            ->setCompanyName('DevHeidelpay')
            ->getCustomerDataArray();

        $Sofort = new Sofort();
        $Sofort->getRequest()->authentification(...$authentication);
        $Sofort->getRequest()->customerAddress(...$customerDetails);
        $Sofort->dryRun = true;

        $this->paymentObject = $Sofort;
    }

    /**
     * Test case for a single sofort authorize
     *
     * @return string payment reference id for the sofort authorize transaction
     * @group connectionTest
     *
     * @test
     *
     * @throws \Exception
     */
    public function authorize()
    {
        $timestamp = $this->getMethod(__METHOD__) . ' ' . date('Y-m-d H:i:s');
        $this->paymentObject->getRequest()->basketData($timestamp, 23.12, $this->currency, $this->secret);
        $this->paymentObject->getRequest()->async('DE', 'https://dev.heidelpay.de');

        $this->paymentObject->authorize();

        /* prepare request and send it to payment api */
        $request = $this->paymentObject->getRequest()->toArray();
        /** @var Response $response */
        list($result, $response) =
            $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);

        $this->assertTrue($response->isSuccess(), 'Transaction failed : ' . print_r($response, 1));
        $this->assertFalse($response->isError(), 'authorize failed : ' . print_r($response->getError(), 1));

        $this->logDataToDebug($result);

        return (string)$response->getPaymentReferenceId();
    }

    /**
     * Test case for Sofort refund
     *
     * @param string $referenceId reference id of the Sofort to refund
     *
     * @return string payment reference id of the Sofort refund transaction
     * @depends authorize
     * @test
     *
     * @group connectionTest
     *
     * @throws \Heidelpay\PhpPaymentApi\Exceptions\UndefinedTransactionModeException
     */
    public function refund($referenceId = null)
    {
        $timestamp = $this->getMethod(__METHOD__) . ' ' . date('Y-m-d H:i:s');
        $this->paymentObject->getRequest()->basketData($timestamp, 3.54, $this->currency, $this->secret);

        /* the refund can not be processed because there will be no receipt automatically on the sandbox */
        $this->paymentObject->dryRun = true;

        $this->paymentObject->refund((string)$referenceId);

        $this->assertEquals('OT.RF', $this->paymentObject->getRequest()->getPayment()->getCode());

        $this->logDataToDebug();
    }
}
