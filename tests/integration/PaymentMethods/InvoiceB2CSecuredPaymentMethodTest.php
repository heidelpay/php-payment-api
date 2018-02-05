<?php

namespace Heidelpay\Tests\PhpPaymentApi\Integration\PaymentMethods;

use Heidelpay\PhpPaymentApi\PaymentMethods\InvoiceB2CSecuredPaymentMethod as Invoice;
use Heidelpay\Tests\PhpPaymentApi\Helper\BasePaymentMethodTest;

/**
 * Invoice B2C secured Test
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
 * @package heidelpay\php-payment-api\tests\integration
 */
class InvoiceB2CSecuredPaymentMethodTest extends BasePaymentMethodTest
{
    //<editor-fold desc="Init">

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
     * @var \Heidelpay\PhpPaymentApi\PaymentMethods\InvoiceB2CSecuredPaymentMethod
     */
    protected $paymentObject;

    /**
     * @var string $authorizeReference
     */
    protected $authorizeReference;

    /**
     * Constructor used to set timezone to utc
     */
    public function __construct()
    {
        date_default_timezone_set('UTC');
        parent::__construct();
    }

    //</editor-fold>

    //<editor-fold desc="Setup">

    /**
     * Set up function will create a invoice object for each test case
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    // @codingStandardsIgnoreStart
    public function _before()
    {
        // @codingStandardsIgnoreEnd
        $authentication = $this->authentication
            ->setTransactionChannel('31HA07BC81856CAD6D8E05CDDE7E2AC8')
            ->getAuthenticationArray();
        $customerDetails = $this->customerData->getCustomerDataArray();

        $Invoice = new Invoice();
        $Invoice->getRequest()->authentification(...$authentication);
        $Invoice->getRequest()->customerAddress(...$customerDetails);
        $this->paymentObject = $Invoice;
    }

    //</editor-fold>

    //<editor-fold desc="Tests">

    /**
     * Test case for a single invoice authorisation
     *
     * @return string payment reference id for the invoice authorize transaction
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
        $this->paymentObject->getRequest()->getFrontend()->setEnabled('FALSE');

        $this->paymentObject->getRequest()->b2cSecured('MRS', '1982-07-12');

        $this->paymentObject->authorize();

        /* verify response */
        $this->assertTrue($this->paymentObject->getResponse()->verifySecurityHash($this->secret, $timestamp));

        /* transaction result */
        $this->assertTrue(
            $this->paymentObject->getResponse()->isSuccess(),
            'Transaction failed : ' . print_r($this->paymentObject->getResponse(), 1)
        );
        $this->assertFalse($this->paymentObject->getResponse()->isPending(), 'authorize is pending');
        $this->assertFalse(
            $this->paymentObject->getResponse()->isError(),
            'authorize failed : ' . print_r($this->paymentObject->getResponse()->getError(), 1)
        );

        $this->logDataToDebug();

        return $this->authorizeReference = (string)$this->paymentObject->getResponse()->getPaymentReferenceId();
    }

    /**
     * Test case for a invoice finalize of a existing authorisation
     *
     * @param $referenceId string payment reference id of the invoice authorisation
     *
     * @return string payment reference id for the prepayment reversal transaction
     * @depends authorize
     * @group connectionTest
     *
     * @test
     *
     * @throws \Exception
     */
    public function finalize($referenceId)
    {
        $timestamp = $this->getMethod(__METHOD__) . ' ' . date('Y-m-d H:i:s');
        $this->paymentObject->getRequest()->basketData($timestamp, 23.12, $this->currency, $this->secret);

        $this->paymentObject->finalize($referenceId);

        /* verify response */
        $this->assertTrue($this->paymentObject->getResponse()->verifySecurityHash($this->secret, $timestamp));

        /* transaction result */
        $this->assertTrue(
            $this->paymentObject->getResponse()->isSuccess(),
            'Transaction failed : ' . print_r($this->paymentObject->getResponse(), 1)
        );
        $this->assertFalse($this->paymentObject->getResponse()->isPending(), 'reversal is pending');
        $this->assertFalse(
            $this->paymentObject->getResponse()->isError(),
            'reversal failed : ' . print_r($this->paymentObject->getResponse()->getError(), 1)
        );

        $this->logDataToDebug();

        return $referenceId;
    }

    /**
     * Test case for a invoice reversal of a existing authorisation
     *
     * @param $referenceId
     *
     * @return string payment reference id for the prepayment reversal transaction
     * @depends authorize
     * @group connectionTest
     *
     * @test
     *
     * @throws \Exception
     */
    public function reversal($referenceId)
    {
        $timestamp = $this->getMethod(__METHOD__) . ' ' . date('Y-m-d H:i:s');
        $this->paymentObject->getRequest()->basketData($timestamp, 23.12, $this->currency, $this->secret);

        /* the refund can not be processed because there will be no receipt automatically on the sandbox */

        $this->paymentObject->reversal($referenceId);

        /* verify response */
        $this->assertTrue($this->paymentObject->getResponse()->verifySecurityHash($this->secret, $timestamp));

        /* transaction result */
        $this->assertTrue(
            $this->paymentObject->getResponse()->isSuccess(),
            'Transaction failed : ' . print_r($this->paymentObject->getResponse(), 1)
        );
        $this->assertFalse($this->paymentObject->getResponse()->isPending(), 'reversal is pending');
        $this->assertFalse(
            $this->paymentObject->getResponse()->isError(),
            'reversal failed : ' . print_r($this->paymentObject->getResponse()->getError(), 1)
        );

        $this->logDataToDebug();

        return (string)$this->paymentObject->getResponse()->getPaymentReferenceId();
    }

    /**
     * Test case for invoice refund
     *
     * @param string $referenceId reference id of the invoice to refund
     *
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

        $this->assertEquals('IV.RF', $this->paymentObject->getRequest()->getPayment()->getCode());

        $this->logDataToDebug();
    }

    //</editor-fold>
}
