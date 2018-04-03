<?php

namespace Heidelpay\Tests\PhpPaymentApi\Unit\PaymentMethods;

use AspectMock\Test as test;
use Heidelpay\PhpPaymentApi\Constants\ApiConfig;
use Heidelpay\PhpPaymentApi\Constants\PaymentMethod;
use Heidelpay\PhpPaymentApi\Constants\TransactionMode;
use Heidelpay\PhpPaymentApi\Constants\TransactionType;
use Heidelpay\PhpPaymentApi\PaymentMethods\InvoiceB2CSecuredPaymentMethod;
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
class PaymentMethodFinalizeTransactionTest extends BasePaymentMethodTest
{
    const PAYMENT_METHOD_SHORT = PaymentMethod::INVOICE;
    const CUSTOMER_BIRTHDAY = '1982-07-12';
    const CUSTOMER_SALUTATION = 'MR';

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
        // @codingStandardsIgnoreEnd
        $authentication = $this->authentication
            ->setTransactionChannel('31HA07BC81856CAD6D8E0B3A3100FBA3')
            ->getAuthenticationArray();
        $customerDetails = $this->customerData->getCustomerDataArray();

        $invoiceB2CSecured = new InvoiceB2CSecuredPaymentMethod();
        $invoiceB2CSecured->getRequest()->authentification(...$authentication);
        $invoiceB2CSecured->getRequest()->customerAddress(...$customerDetails);
        $invoiceB2CSecured->getRequest()->b2cSecured(self::CUSTOMER_SALUTATION, self::CUSTOMER_BIRTHDAY);
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

    //</editor-fold>

    //<editor-fold desc="Tests">

    //<editor-fold desc="Common">

    /**
     * Verify transaction code is set depending on payment method.
     *
     * @dataProvider transactionCodeProvider
     * @test
     *
     * @param $method
     * @param $parameters
     */
    public function verifyTransactionReturnsThePaymentObject($method, $parameters)
    {
        $object = call_user_func([$this->paymentObject, $method], $parameters);
        $this->assertSame($this->paymentObject, $object);
    }

    //</editor-fold>

    /**
     * Verify finalize parameters generated as expected
     *
     * @test
     */
    public function finalizeParametersShouldBeSetUpAsExpected()
    {
        $timestamp = 'InvoiceB2CSecuredPaymentMethodTest::finalize 2018-03-29 14:30:00';
        $this->paymentObject->getRequest()->basketData(
            $timestamp,
            self::TEST_AMOUNT,
            $this->currency,
            $this->secret
        );

        $this->paymentObject->finalize(self::REFERENCE_ID);

        list($firstName, $lastName, , $shopperId, $street, $state, $zip, $city, $country, $email) =
            $this->customerData->getCustomerDataArray();

        list($securitySender, $userLogin, $userPassword, $transactionChannel, ) =
            $this->authentication->getAuthenticationArray();

        // this is done to avoid syntax warnings
        $object = $this->paymentObject;
        
        $expected = [
            'ADDRESS.CITY' => $city,
            'ADDRESS.COUNTRY' => $country,
            'ADDRESS.STATE' => $state,
            'ADDRESS.STREET' => $street,
            'ADDRESS.ZIP' => $zip,
            'CONTACT.EMAIL' => $email,
            'CRITERION.PAYMENT_METHOD' => $object::getClassName(),
            'CRITERION.SECRET' => '05fe5a10224f2686b045b2ebd056f079105e9c740d2bfc33222aa7467d587a185c96f8031d' .
                'e59612bf3cea321cc07fb54c296fd8338021e5200879507a4b44be',
            'CRITERION.SDK_NAME' => 'Heidelpay\\PhpPaymentApi',
            'CRITERION.SDK_VERSION' => ApiConfig::SDK_VERSION,
            'FRONTEND.ENABLED' => 'FALSE',
            'FRONTEND.MODE' => 'WHITELABEL',
            'IDENTIFICATION.SHOPPERID' => $shopperId,
            'IDENTIFICATION.TRANSACTIONID' => $timestamp,
            'IDENTIFICATION.REFERENCEID' => self::REFERENCE_ID,
            'NAME.BIRTHDATE' => self::CUSTOMER_BIRTHDAY,
            'NAME.GIVEN' => $firstName,
            'NAME.FAMILY' => $lastName,
            'NAME.SALUTATION' => self::CUSTOMER_SALUTATION,
            'PAYMENT.CODE' => self::PAYMENT_METHOD_SHORT . '.' . TransactionType::FINALIZE,
            'PRESENTATION.AMOUNT' => self::TEST_AMOUNT,
            'PRESENTATION.CURRENCY' => $this->currency,
            'REQUEST.VERSION' => '1.0',
            'SECURITY.SENDER' => $securitySender,
            'TRANSACTION.CHANNEL' => $transactionChannel,
            'TRANSACTION.MODE' => TransactionMode::CONNECTOR_TEST,
            'USER.LOGIN' => $userLogin,
            'USER.PWD' => $userPassword,
        ];

        $this->assertThat($this->paymentObject->getRequest()->toArray(), $this->arraysMatchExactly($expected));
    }

    //</editor-fold>
}
