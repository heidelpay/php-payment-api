<?php

namespace Heidelpay\Tests\PhpPaymentApi\Unit\PaymentMethods;

use AspectMock\Test as test;
use Heidelpay\PhpPaymentApi\Constants\PaymentMethod;
use Heidelpay\PhpPaymentApi\Constants\TransactionType;
use Heidelpay\Tests\PhpPaymentApi\Helper\BasePaymentMethodTest;

/**
 * This test class performs tests to verify the general behaviour of each payment method thus verifies that certain
 * request parameters are set depending on the method.
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
class GenericPaymentMethodTest extends BasePaymentMethodTest
{
    private $paymentMethodNamespace = "Heidelpay\\PhpPaymentApi\\PaymentMethods\\";

    //<editor-fold desc="Init">

    /**
     *  Account holder
     *
     * @var string $holder
     */
    protected $holder = 'Heidel Berger-Payment';

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
     * Customers IBAN
     *
     * @var string $iban
     */
    protected $iban = 'DE89370400440532013000';

    //</editor-fold>

    //<editor-fold desc="Setup">

    /**
     * Set up function will create a payment method object for each test case
     */
    // @codingStandardsIgnoreStart
    public function _before()
    {
        // nothing to do here since the object creation and mocking takes place in the test method.
    }

    /**
     * Tear down function will remove all registered test doubles (i.e. Mocks)
     */
    // @codingStandardsIgnoreStart
    public function _after()
    {
        $this->paymentObject = null;
        test::clean();
    }

    //</editor-fold>

    //<editor-fold desc="dataProviders">

    /**
     * @return array
     */
    public static function paymentMethodProvider()
    {
        return [
            ['CreditCardPaymentMethod', PaymentMethod::CREDIT_CARD],
            ['DebitCardPaymentMethod', PaymentMethod::DEBIT_CARD],
            ['DirectDebitB2CSecuredPaymentMethod', PaymentMethod::DIRECT_DEBIT],
            ['DirectDebitPaymentMethod', PaymentMethod::DIRECT_DEBIT],
            ['EasyCreditPaymentMethod', PaymentMethod::HIRE_PURCHASE, 'EASYCREDIT'],
            ['EPSPaymentMethod', PaymentMethod::ONLINE_TRANSFER, 'EPS'],
            ['GiropayPaymentMethod', PaymentMethod::ONLINE_TRANSFER, 'GIROPAY'],
            ['IDealPaymentMethod', PaymentMethod::ONLINE_TRANSFER, 'IDEAL'],
            ['InvoiceB2CSecuredPaymentMethod', PaymentMethod::INVOICE],
            ['InvoicePaymentMethod', PaymentMethod::INVOICE],
            ['PayPalPaymentMethod', PaymentMethod::VIRTUAL_ACCOUNT, 'PAYPAL'],
            ['PostFinanceCardPaymentMethod', PaymentMethod::ONLINE_TRANSFER, 'PFCARD'],
            ['PostFinanceEFinancePaymentMethod', PaymentMethod::ONLINE_TRANSFER, 'PFEFINANCE'],
            ['PrepaymentPaymentMethod', PaymentMethod::PREPAYMENT],
            ['Przelewy24PaymentMethod', PaymentMethod::ONLINE_TRANSFER, 'PRZELEWY24'],
            ['SantanderInvoicePaymentMethod', PaymentMethod::INVOICE, 'SANTANDER'],
            ['SofortPaymentMethod', PaymentMethod::ONLINE_TRANSFER, 'SOFORT'],
            ['PayolutionInvoicePaymentMethod', PaymentMethod::INVOICE, 'PAYOLUTION_DIRECT']
        ];
    }

    //</editor-fold>

    //<editor-fold desc="Tests">

    /**
     * Verify transaction code is set depending on payment method.
     *
     * @dataProvider paymentMethodProvider
     * @test
     *
     * @param $paymentMethodClass
     * @param $paymentCode
     * @param null $brand
     */
    public function verifyPaymentMethodPresentsAsExpected($paymentMethodClass, $paymentCode, $brand = null)
    {
        $this->log(' Testing payment method: ' . $paymentMethodClass . '...');
        $paymentMethodClassPath = $this->paymentMethodNamespace . $paymentMethodClass;

        $this->paymentObject = new $paymentMethodClassPath();
        $this->assertSame($paymentMethodClassPath, get_class($this->paymentObject));
        $this->mockCurlAdapter();

        $returnObject = $this->paymentObject->refund('');
        $this->assertSame($paymentMethodClassPath, get_class($returnObject));

        $requestArray = $this->paymentObject->getRequest()->toArray();
        $this->assertArrayHasKey('PAYMENT.CODE', $requestArray);
        $this->assertSame($paymentCode . '.' . TransactionType::REFUND, $requestArray['PAYMENT.CODE']);
        $this->assertSame($paymentMethodClass, $requestArray['CRITERION.PAYMENT_METHOD']);
        if (null !== $brand) {
            $this->assertArrayHasKey('ACCOUNT.BRAND', $requestArray);
            $this->assertSame($brand, $requestArray['ACCOUNT.BRAND']);
        } else {
            $this->assertArrayNotHasKey('ACCOUNT.BRAND', $requestArray);
        }

        $this->success();
    }

    //</editor-fold>
}
