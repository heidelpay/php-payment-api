<?php

namespace Heidelpay\Tests\PhpPaymentApi\Integration\PaymentMethods;

use Heidelpay\PhpPaymentApi\Response;
use Heidelpay\PhpPaymentApi\PaymentMethods\PostFinanceCardPaymentMethod as PostFinanceCard;
use Heidelpay\Tests\PhpPaymentApi\Helper\BasePaymentMethodTest;

/**
 * PostFinanceCard Test
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
 * @package heidelpay\php-payment-api\tests\integration
 */
class PostFinanceCardPaymentMethodTest extends BasePaymentMethodTest
{
    /**
     * Transaction currency
     *
     * @var string currency
     */
    protected $currency = 'CHF';

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
     * @var \Heidelpay\PhpPaymentApi\PaymentMethods\PostFinanceCardPaymentMethod
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
     * Set up function will create a PostFinanceCard object for each test case
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    // @codingStandardsIgnoreStart
    public function _before()
    {
        $authentication = $this->authentication
            ->setTransactionChannel('31HA07BC817E5CF74624746925703A51')
            ->getAuthenticationArray();
        $customerDetails = $this->customerData
            ->setCompanyName('DevHeidelpay')
            ->setAddressCountry('CH')
            ->getCustomerDataArray();

        $PostFinanceCard = new PostFinanceCard();
        $PostFinanceCard->getRequest()->authentification(...$authentication);
        $PostFinanceCard->getRequest()->customerAddress(...$customerDetails);
        $PostFinanceCard->dryRun = true;

        $this->paymentObject = $PostFinanceCard;
    }

    /**
     * Test case for a single PostFinanceCard authorize
     *
     * @return string payment reference id for the PostFinanceCard authorize transaction
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
        list($result, $response) = $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);

        $this->assertTrue($response->isSuccess(), 'Transaction failed : ' . print_r($response, 1));
        $this->assertFalse($response->isError(), 'authorize failed : ' . print_r($response->getError(), 1));

        $this->logDataToDebug($result);

        return (string)$response->getPaymentReferenceId();
    }
}
