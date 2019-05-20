<?php
/**
 * Tests to verify PIS payment method.
 *
 * Connection tests can fail due to network issues and scheduled down times.
 * This does not have to mean that your integration is broken. Please verify the given debug information
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2019-present heidelpay GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author  Simon Gabriel
 *
 * @package heidelpay\php-payment-api\tests\integration
 */

namespace Heidelpay\Tests\PhpPaymentApi\Integration\PaymentMethods;

use Heidelpay\PhpPaymentApi\Constants\PaymentMethod;
use Heidelpay\PhpPaymentApi\Constants\TransactionType;
use Heidelpay\PhpPaymentApi\Exceptions\UndefinedTransactionModeException;
use Heidelpay\PhpPaymentApi\PaymentMethods\PISPaymentMethod;
use Heidelpay\PhpPaymentApi\Response;
use Heidelpay\Tests\PhpPaymentApi\Helper\BasePaymentMethodTest;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;

class PISPaymentMethodTest extends BasePaymentMethodTest
{
    /** @var string $currency */
    protected $currency = 'EUR';

    /**
     * The secret will be used to generate a hash using transaction id + secret. This hash can be used to
     * verify the payment response. Can be used for brute force protection.
     *
     * @var string $secret
     */
    protected $secret = 'Heidelpay-PhpPaymentApi';

    /** @var PISPaymentMethod $paymentObject*/
    protected $paymentObject;

    /**
     * Constructor used to set timezone to UTC.
     */
    public function __construct()
    {
        date_default_timezone_set('UTC');

        parent::__construct();
    }

    /**
     * Set up function will create a PIS object for each test case
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    // @codingStandardsIgnoreStart
    public function _before()
    {
        // @codingStandardsIgnoreEnd
        $authentication = $this->authentication->setTransactionChannel('31HA07BC8176B8333AC423CC4EC0C2D4')->getAuthenticationArray();
        $customerDetails = $this->customerData->setCompanyName('DevHeidelpay')->getCustomerDataArray();

        $pis = new PISPaymentMethod();
        $pis->getRequest()->authentification(...$authentication);
        $pis->getRequest()->customerAddress(...$customerDetails);
        $pis->dryRun = true;

        $this->paymentObject = $pis;
    }

    /**
     * Test case for a single PIS authorize
     *
     * @return string payment reference id for the authorize transaction
     * @throws UndefinedTransactionModeException
     * @throws AssertionFailedError
     * @group connectionTest
     *
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

        return (string)$response->getPaymentReferenceId();
    }

    /**
     * Test case for PIS refund
     *
     * @param string Reference id of the PIS to refund
     *
     * @throws UndefinedTransactionModeException
     * @throws ExpectationFailedException
     * @throws Exception
     * @depends authorize
     * @test
     *
     * @group connectionTest
     *
     */
    public function refund($referenceId = null)
    {
        $timestamp = $this->getMethod(__METHOD__) . ' ' . date('Y-m-d H:i:s');
        $this->paymentObject->getRequest()->basketData($timestamp, 3.54, $this->currency, $this->secret);

        /* the refund can not be processed because there will be no receipt automatically on the sandbox */
        $this->paymentObject->dryRun = true;

        $this->paymentObject->refund((string)$referenceId);

        $this->assertEquals(PaymentMethod::ONLINE_TRANSFER . '.' . TransactionType::REFUND, $this->paymentObject->getRequest()->getPayment()->getCode());

        $this->logDataToDebug();
    }
}
