<?php

namespace Heidelpay\Tests\PhpPaymentApi\Unit\PaymentMethods;

use AspectMock\Test as test;
use Heidelpay\PhpPaymentApi\Constants\ApiConfig;
use Heidelpay\PhpPaymentApi\Constants\PaymentMethod;
use Heidelpay\PhpPaymentApi\Constants\TransactionType;
use Heidelpay\PhpPaymentApi\PaymentMethods\InvoiceB2CSecuredPaymentMethod;
use Heidelpay\PhpPaymentApi\Request;
use Heidelpay\Tests\PhpPaymentApi\Helper\BasePaymentMethodTest;

/**
 * This test class verifies the behaviour in all transaction represented by one payment method.
 * There is no actual communication to the server since the curl adapter is being mocked.
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-payment-api/
 *
 * @author  Simon Gabriel
 *
 * @package heidelpay\php-payment-api\tests\unit
 */
class PaymentMethodFactoringTest extends BasePaymentMethodTest
{
    const PAYMENT_METHOD_SHORT = PaymentMethod::INVOICE;
    const CUSTOMER_BIRTHDAY = '1982-07-12';
    const CUSTOMER_SALUTATION = 'MR';

    protected $expectedRequestVars = [];

    //<editor-fold desc="Init">

    /**
     * Transaction currency
     *
     * @var string $currency
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
     * @var string $secret
     */
    protected $secret = 'Heidelpay-PhpPaymentApi';

    /**
     * PaymentObject
     *
     * @var DirectDebitB2CSecuredPaymentMethod $paymentObject
     */
    protected $paymentObject;

    //</editor-fold>

    //<editor-fold desc="Setup">

    /**
     * Set up function will create a payment method object for each test case
     *
     * @throws \Exception
     */
    // @codingStandardsIgnoreStart
    public function _before()
    {
        $invoiceB2CSecured = new InvoiceB2CSecuredPaymentMethod();
        $request = $invoiceB2CSecured->getRequest();

        $request->getIdentification()->setShopperid('OriginalShopperId');
        $request->getIdentification()->setInvoiceid('OriginalInvoiceId');
        $invoiceB2CSecured->dryRun = false;
        $this->paymentObject = $invoiceB2CSecured;

        $this->mockCurlAdapter();
    }

    /**
     * Tear down function will remove all registered test doubles (i.e. Mocks)
     */
    // @codingStandardsIgnoreStart
    public function _after()
    {
        // @codingStandardsIgnoreEnd
        $this->paymentObject = null;
        test::clean();
    }

    //</editor-fold>

    //<editor-fold desc="dataProviders">

    /**
     * @return array
     */
    public static function transactionCodeProvider()
    {
        return [
            ['finalize', null, TransactionType::FINALIZE]
        ];
    }

    /**
     * @dataProvider FactoringParameterShouldBeSetAsExpectedDataProvider
     * @test
     *
     * @param $invoiceId
     * @param $shopperId
     * @param $expectedInvoiceId
     * @param $expectedShopperId
     *
     * @return array
     */
    public function factoringParameterShouldBeSetAsExpected($invoiceId, $shopperId, $expectedInvoiceId, $expectedShopperId)
    {
        $request = $this->paymentObject->getRequest();

        $request->getIdentification()->setShopperid('OriginalShopperId');
        $request->getIdentification()->setInvoiceid('OriginalInvoiceId');

        if ($shopperId) {
            $request->factoring($invoiceId, $shopperId);
        } else {
            $request->factoring($invoiceId);
        }

        $this->expectedRequestVars = [
            'CRITERION.SDK_NAME' => 'Heidelpay\PhpPaymentApi',
            'CRITERION.SDK_VERSION' => ApiConfig::SDK_VERSION,
            'FRONTEND.ENABLED' => 'TRUE',
            'FRONTEND.MODE' => 'WHITELABEL',
            'IDENTIFICATION.INVOICEID' => $expectedInvoiceId,
            'IDENTIFICATION.SHOPPERID' => $expectedShopperId,
            'REQUEST.VERSION' => '1.0',
            'TRANSACTION.MODE' => 'CONNECTOR_TEST',
        ];

        $this->assertEquals($this->expectedRequestVars, $request->toArray());
        return $this->expectedRequestVars;
    }

    public function FactoringParameterShouldBeSetAsExpectedDataProvider()
    {
        return [
            'shopperid null' => ['invoice01', null, 'invoice01', 'OriginalShopperId'],
            'shopperid set separately' => ['invoice01', 'shopper01', 'invoice01', 'shopper01'],
        ];
    }

    /**
     * @test
     *
     * @param $requestArray
     *
     * @return mixed
     */
    public function arrayShouldBeMappedToObjectAsExpected()
    {
        $responseArray = [
            'CRITERION_SDK_NAME' => 'Heidelpay\PhpPaymentApi',
            'CRITERION.SDK_VERSION' => ApiConfig::SDK_VERSION,
            'FRONTEND_ENABLED' => 'TRUE',
            'FRONTEND_MODE' => 'WHITELABEL',
            'IDENTIFICATION_INVOICEID' => 'OriginalInvoiceId',
            'IDENTIFICATION_SHOPPERID' => 'OriginalShopperId',
            'REQUEST_VERSION' => '1.0',
            'TRANSACTION_MODE' => 'CONNECTOR_TEST',
        ];

        $this->assertEquals($this->getPaymentObject()->getRequest(), Request::fromPost($responseArray));
        return $this->paymentObject->getRequest();
    }

    //</editor-fold>
}
