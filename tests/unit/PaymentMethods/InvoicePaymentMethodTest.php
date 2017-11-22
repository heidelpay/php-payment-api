<?php

namespace Heidelpay\Tests\PhpApi\Unit\PaymentMethods;

use AspectMock\Proxy\InstanceProxy;
use AspectMock\Test as test;
use Heidelpay\PhpApi\PaymentMethods\InvoicePaymentMethod;
use Heidelpay\Tests\PhpApi\Helper\BasePaymentMethodTest;

/**
 *  Invoice B2C Secured test
 *
 *  Warning:
 *  - Use of the following code is only allowed with this sandbox credit card information.
 *
 *  - Using this code or even parts of it with real credit card information  is a violation
 *  of the payment card industry standard aka pci3.
 *
 *  - You are not allowed to save, store and/or process credit card information any time with your systems.
 *    Always use Heidelpay payment frame solution for a pci3 conform credit card integration.
 *
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author  Simon Gabriel
 *
 * @package  Heidelpay
 * @subpackage PhpApi
 * @category UnitTest
 */
class InvoicePaymentMethodTest extends BasePaymentMethodTest
{
    const PAYMENT_METHOD = 'InvoicePaymentMethod';
    const PAYMENT_METHOD_SHORT = 'IV';
    const CUSTOMER_BIRTHDAY = '1982-07-12';
    const CUSTOMER_SALUTATION = 'MRS';

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
    protected $secret = 'Heidelpay-PhpApi';

    /**
     * PaymentObject
     *
     * @var InvoicePaymentMethod $paymentObject
     */
    protected $paymentObject;

    //</editor-fold>

    //<editor-fold desc="Setup">

    /**
     * Set up function will create a payment method object for each test case
     */
    // @codingStandardsIgnoreStart
    public function _before()
    {
        // @codingStandardsIgnoreEnd
        $this->authentication->setTransactionChannel('31HA07BC8142C5A171744F3D6D155865');

        $creditCard = new InvoicePaymentMethod();
        $creditCard->getRequest()->authentification(...$this->authentication->getAuthenticationArray());
        $creditCard->getRequest()->customerAddress(...$this->customerData->getCustomerDataArray());
        $creditCard->_dryRun = false;

        $this->paymentObject = $creditCard;

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
            ['authorize', null, 'PA'],
            ['refund', null,'RF'],
            ['reversal', null,'RV']
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
     * @param $transactionCode
     */
    public function verifyTransactionCodeIsBeingSetCorrectly($method, $parameters, $transactionCode)
    {
        $paymentParameterGroup = $this->paymentObject->getRequest()->getPayment();

        // verify it has no transaction code before
        $payment_code = $paymentParameterGroup->getCode();
        $this->assertCount(1, explode('.', $payment_code));

        call_user_func([$this->paymentObject, $method], $parameters);

        // verify the correct transaction code has been appended
        $payment_code = $paymentParameterGroup->getCode();
        $this->assertEquals(self::PAYMENT_METHOD_SHORT . '.' . $transactionCode, $payment_code);
    }

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

    /**
     * Verify sendPost id called once in each payment method call.
     *
     * @dataProvider transactionCodeProvider
     * @test
     *
     * @param $method
     * @param $parameters
     */
    public function verifySendPostIsCalledOnceInEachPaymentMethodCall($method, $parameters)
    {
        call_user_func([$this->paymentObject, $method], $parameters);

        /** @var InstanceProxy $adapter */
        $adapter = $this->paymentObject->getAdapter();
        $adapter->verifyInvokedOnce('sendPost');
    }

    /**
     * Verify implicit prepareRequest call sets criterion payment method as expected
     *
     * @test
     */
    public function prepareRequestShouldSetCriterionPaymentMethod()
    {
        $criterionParameterGroup = $this->paymentObject->getRequest()->getCriterion();

        // verify initial state
        $this->assertNull($criterionParameterGroup->getPaymentMethod());

        $this->paymentObject->authorize();

        // verify the criterion values changed as expected
        $this->assertEquals(self::PAYMENT_METHOD, $criterionParameterGroup->getPaymentMethod());
    }

    //</editor-fold>

    /**
     * Verify authorize parameters generated as expected
     *
     * @test
     */
    public function authorizeParametersShouldBeSetUpAsExpected()
    {
        $timestamp = 'InvoicePaymentMethodTest::authorizeParametersShouldBeSetUpAsExpected 2017-11-22 14:01:52';
        $this->paymentObject->getRequest()->basketData(
            $timestamp,
            self::TEST_AMOUNT,
            $this->currency,
            $this->secret
        );

        $frontendEnabled = 'FALSE';
        $this->paymentObject->getRequest()->getFrontend()->set('enabled', $frontendEnabled);
        $this->paymentObject->getRequest()->b2cSecured(self::CUSTOMER_SALUTATION, self::CUSTOMER_BIRTHDAY);
        $this->paymentObject->getRequest()->getFrontend()->setEnabled($frontendEnabled);

        $this->paymentObject->authorize();

        list($firstName, $lastName, , $shopperId, $street, $state, $zip, $city, $country, $email) =
            $this->customerData->getCustomerDataArray();

        list($securitySender, $userLogin, $userPassword, $transactionChannel, ) =
            $this->authentication->getAuthenticationArray();

        $expected = [
            'ADDRESS.CITY' => $city,
            'ADDRESS.COUNTRY' => $country,
            'ADDRESS.STATE' => $state,
            'ADDRESS.STREET' => $street,
            'ADDRESS.ZIP' => $zip,
            'CONTACT.EMAIL' => $email,
            'CRITERION.PAYMENT_METHOD' => self::PAYMENT_METHOD,
            'CRITERION.SECRET' => 'af26fba9ea67fdcc5065a55800b18179f4f75be091959f86e73ed12dda3537a1ccf671eb5'.
                'b2d15098e60dc7aff5f7f05ffed6d2794ed9367388e4d29512a6980',
            'CRITERION.SDK_NAME' => 'Heidelpay\\PhpApi',
            'CRITERION.SDK_VERSION' => '17.9.27',
            'FRONTEND.ENABLED' => $frontendEnabled,
            'FRONTEND.MODE' => 'WHITELABEL',
            'IDENTIFICATION.SHOPPERID' => $shopperId,
            'IDENTIFICATION.TRANSACTIONID' => $timestamp,
            'NAME.BIRTHDATE' => self::CUSTOMER_BIRTHDAY,
            'NAME.GIVEN' => $firstName,
            'NAME.FAMILY' => $lastName,
            'NAME.SALUTATION' => self::CUSTOMER_SALUTATION,
            'PAYMENT.CODE' => self::PAYMENT_METHOD_SHORT . '.PA',
            'PRESENTATION.AMOUNT' => self::TEST_AMOUNT,
            'PRESENTATION.CURRENCY' => $this->currency,
            'REQUEST.VERSION' => '1.0',
            'SECURITY.SENDER' => $securitySender,
            'TRANSACTION.CHANNEL' => $transactionChannel,
            'TRANSACTION.MODE' => 'CONNECTOR_TEST',
            'USER.LOGIN' => $userLogin,
            'USER.PWD' => $userPassword
        ];

        $this->assertThat($this->paymentObject->getRequest()->convertToArray(), $this->arraysMatchExactly($expected));
    }

    /**
     * Verify refund parameters generated as expected
     *
     * @test
     */
    public function refundParametersShouldBeSetUpAsExpected()
    {
        $timestamp = 'InvoiceB2CSecuredPaymentMethodTest::refundParametersShouldBeSetUpAsExpected 2017-11-22 13:21:17';
        $this->paymentObject->getRequest()->basketData(
            $timestamp,
            self::TEST_AMOUNT,
            $this->currency,
            $this->secret
        );

        $this->paymentObject->refund(self::REFERENCE_ID);

        list($firstName, $lastName, , $shopperId, $street, $state, $zip, $city, $country, $email) =
            $this->customerData->getCustomerDataArray();

        list($securitySender, $userLogin, $userPassword, $transactionChannel, ) =
            $this->authentication->getAuthenticationArray();

        $expected = [
            'ADDRESS.CITY' => $city,
            'ADDRESS.COUNTRY' => $country,
            'ADDRESS.STATE' => $state,
            'ADDRESS.STREET' => $street,
            'ADDRESS.ZIP' => $zip,
            'CONTACT.EMAIL' => $email,
            'CRITERION.PAYMENT_METHOD' => self::PAYMENT_METHOD,
            'CRITERION.SECRET' => '75064bb4d5de92d9ea1e8e865f50d3fa603054dc08a27f1ac4ff30d99ede16900ce22838cf57'.
                '62e2570f6a7d2b911fe5b9b599ff8373e97174d2637fae075db7',
            'CRITERION.SDK_NAME' => 'Heidelpay\\PhpApi',
            'CRITERION.SDK_VERSION' => '17.9.27',
            'FRONTEND.ENABLED' => 'FALSE',
            'FRONTEND.MODE' => 'WHITELABEL',
            'IDENTIFICATION.SHOPPERID' => $shopperId,
            'IDENTIFICATION.TRANSACTIONID' => $timestamp,
            'IDENTIFICATION.REFERENCEID' => self::REFERENCE_ID,
            'NAME.GIVEN' => $firstName,
            'NAME.FAMILY' => $lastName,
            'PAYMENT.CODE' => self::PAYMENT_METHOD_SHORT . '.RF',
            'PRESENTATION.AMOUNT' => self::TEST_AMOUNT,
            'PRESENTATION.CURRENCY' => $this->currency,
            'REQUEST.VERSION' => '1.0',
            'SECURITY.SENDER' => $securitySender,
            'TRANSACTION.CHANNEL' => $transactionChannel,
            'TRANSACTION.MODE' => 'CONNECTOR_TEST',
            'USER.LOGIN' => $userLogin,
            'USER.PWD' => $userPassword,
        ];

        $this->assertThat($this->paymentObject->getRequest()->convertToArray(), $this->arraysMatchExactly($expected));
    }

    /**
     * Verify reversal parameters generated as expected
     *
     * @test
     */
    public function reversalParametersShouldBeSetUpAsExpected()
    {
        $timestamp = 'InvoicePaymentMethodTest::reversalParametersShouldBeSetUpAsExpected 2017-11-22 14:14:35';
        $this->paymentObject->getRequest()->basketData(
            $timestamp,
            self::TEST_AMOUNT,
            $this->currency,
            $this->secret
        );

        $this->paymentObject->reversal(self::REFERENCE_ID);

        list($firstName, $lastName, , $shopperId, $street, $state, $zip, $city, $country, $email) =
            $this->customerData->getCustomerDataArray();

        list($securitySender, $userLogin, $userPassword, $transactionChannel, ) =
            $this->authentication->getAuthenticationArray();

        $expected = [
            'ADDRESS.CITY' => $city,
            'ADDRESS.COUNTRY' => $country,
            'ADDRESS.STATE' => $state,
            'ADDRESS.STREET' => $street,
            'ADDRESS.ZIP' => $zip,
            'CONTACT.EMAIL' => $email,
            'CRITERION.PAYMENT_METHOD' => self::PAYMENT_METHOD,
            'CRITERION.SECRET' => '8bf5579efb4ab842e638a4df0819799aa9e8a22fa85c815edf6c9ad326609ad9942ace279' .
                '12d53b75f1afedf6ae593e749755a4d8e8174ae919cd92e26e50613',
            'CRITERION.SDK_NAME' => 'Heidelpay\\PhpApi',
            'CRITERION.SDK_VERSION' => '17.9.27',
            'FRONTEND.ENABLED' => 'FALSE',
            'FRONTEND.MODE' => 'WHITELABEL',
            'IDENTIFICATION.SHOPPERID' => $shopperId,
            'IDENTIFICATION.TRANSACTIONID' => $timestamp,
            'IDENTIFICATION.REFERENCEID' => self::REFERENCE_ID,
            'NAME.GIVEN' => $firstName,
            'NAME.FAMILY' => $lastName,
            'PAYMENT.CODE' => self::PAYMENT_METHOD_SHORT . '.RV',
            'PRESENTATION.AMOUNT' => self::TEST_AMOUNT,
            'PRESENTATION.CURRENCY' => $this->currency,
            'REQUEST.VERSION' => '1.0',
            'SECURITY.SENDER' => $securitySender,
            'TRANSACTION.CHANNEL' => $transactionChannel,
            'TRANSACTION.MODE' => 'CONNECTOR_TEST',
            'USER.LOGIN' => $userLogin,
            'USER.PWD' => $userPassword
        ];

        $this->assertThat($this->paymentObject->getRequest()->convertToArray(), $this->arraysMatchExactly($expected));
    }

    //</editor-fold>
}
