<?php

namespace Heidelpay\Tests\PhpApi\Unit\PaymentMethods;

use AspectMock\Proxy\InstanceProxy;
use AspectMock\Test as AspectMockTest;
use Heidelpay\PhpApi\PaymentMethods\CreditCardPaymentMethod;
use Heidelpay\Tests\PhpApi\Helper\BasePaymentMethodTest;

/**
 *  Credit card test
 *
 *  Connection tests can fail due to network issues and scheduled down times.
 *  This does not have to mean that your integration is broken. Please verify the given debug information
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
 * @author  Jens Richter
 *
 * @package  Heidelpay
 * @subpackage PhpApi
 * @category UnitTest
 */
class CreditCardPaymentMethodTest extends BasePaymentMethodTest
{
    const REFERENCE_ID = 'http://www.heidelpay.de';
    const PAYMENT_FRAME_ORIGIN = self::REFERENCE_ID;
    const CSS_PATH = self::REFERENCE_ID;
    const TEST_AMOUNT = 23.12;
    const PAYMENT_METHOD = 'CreditCardPaymentMethod';

    //<editor-fold desc="Init">

    /**
     *  Account holder
     *
     * @var string Account holder
     */
    protected $holder = 'Heidel Berger-Payment';

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
    protected $secret = 'Heidelpay-PhpApi';

    /**
     * Credit card number
     * Do not use real credit card information for this test. For more details read the information
     * on top of this test class.
     *
     * @var string credit card number
     */
    protected $creditCartNumber = '4711100000000000';
    /**
     * Credit card brand
     * Do not use real credit card information for this test. For more details read the information
     * on top of this test class.
     *
     * @var string credit card brand
     */
    protected $creditCardBrand = 'VISA';
    /**
     * Credit card verification
     * Do not use real credit card information for this test. For more details read the information
     * on top of this test class.
     *
     * @var string credit card verification
     */
    protected $creditCardVerification = '123';
    /**
     * Credit card expiry month
     *
     * @var string credit card verification
     */
    protected $creditCardExpiryMonth = '04';
    /**
     * Credit card expiry year
     *
     * @var string credit card year
     */
    protected $creditCardExpiryYear = '2040';

    //</editor-fold>

    //<editor-fold desc="Setup">

    /**
     * Set up function will create a credit card object for each test case
     */
    // @codingStandardsIgnoreStart
    public function _before()
    {
        // @codingStandardsIgnoreEnd
        $this->authentication->setTransactionChannel('31HA07BC8142C5A171744F3D6D155865');

        $creditCard = new CreditCardPaymentMethod();
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
        AspectMockTest::clean();
    }

    //</editor-fold>

    //<editor-fold desc="dataProvider">

    /**
     * @return array
     */
    public static function transactionCodeProvider()
    {
        return [
            ['authorize', null, 'PA'],
            ['debit', null,'DB'],
            ['registration', null,'RG'],
            ['authorizeOnRegistration', null,'PA'],
            ['debitOnRegistration', null,'DB'],
            ['refund', null,'RF'],
            ['reversal', null,'RV'],
            ['rebill', null,'RB']
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
        $this->assertCount(2, explode('.', $payment_code));
        $this->assertEquals($transactionCode, explode('.', $payment_code)[1]);
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
    public function prepareRequestShouldSetCriterionPaymentMethodToCreditCard()
    {
        $criterionParameterGroup = $this->paymentObject->getRequest()->getCriterion();

        // verify initial state
        $this->assertNull($criterionParameterGroup->getPaymentMethod());

        $this->paymentObject->authorize();

        // verify the criterion values changed as expected
        $this->assertEquals('CreditCardPaymentMethod', $criterionParameterGroup->getPaymentMethod());
    }

    //</editor-fold>

    /**
     * Verify registration parameters generated as expected
     *
     * @test
     */
    public function registrationParametersShouldBeSetUpAsExpected()
    {
        $timestamp = 'CreditCardPaymentMethodTest::authorizeParameterJsonShouldBeSetUpAsExpected 2017-10-26 15:41:12';
        $this->paymentObject->getRequest()->basketData(
            $timestamp,
            self::TEST_AMOUNT,
            $this->currency,
            $this->secret
        );

        $paymentFrameOrigin = 'http://www.heidelpay.de';
        $cssPath = 'http://www.heidelpay.de';
        $preventAsyncRedirect = 'FALSE';
        $this->paymentObject->registration(
            $paymentFrameOrigin,
            $preventAsyncRedirect,
            $cssPath
        );

        /* disable frontend (iframe) and submit the credit card information directly (only for testing) */
        $frontendEnabled = 'FALSE';
        $this->paymentObject->getRequest()->getFrontend()->setEnabled($frontendEnabled);
        $this->paymentObject->getRequest()->getAccount()->setHolder($this->holder);
        $this->paymentObject->getRequest()->getAccount()->setNumber($this->creditCartNumber);
        $this->paymentObject->getRequest()->getAccount()->set('expiry_month', $this->creditCardExpiryMonth);
        $this->paymentObject->getRequest()->getAccount()->set('expiry_year', $this->creditCardExpiryYear);
        $this->paymentObject->getRequest()->getAccount()->setBrand($this->creditCardBrand);
        $this->paymentObject->getRequest()->getAccount()->set('verification', $this->creditCardVerification);

        list($firstName, $lastName, , $shopperId, $street, $state, $zip, $city, $country, $email) =
            $this->customerData->getCustomerDataArray();

        list($securitySender, $userLogin, $userPassword, $transactionChannel, ) =
            $this->authentication->getAuthenticationArray();

        $parameters =
            [
                'ACCOUNT.BRAND' => $this->creditCardBrand,
                'ACCOUNT.EXPIRY_MONTH' => $this->creditCardExpiryMonth,
                'ACCOUNT.EXPIRY_YEAR' => $this->creditCardExpiryYear,
                'ACCOUNT.HOLDER' => $this->holder,
                'ACCOUNT.NUMBER' => $this->creditCartNumber,
                'ACCOUNT.VERIFICATION' => $this->creditCardVerification,
                'ADDRESS.CITY' => $city,
                'ADDRESS.COUNTRY' => $country,
                'ADDRESS.STATE' => $state,
                'ADDRESS.STREET' => $street,
                'ADDRESS.ZIP' => $zip,
                'CONTACT.EMAIL' => $email,
                'CRITERION.PAYMENT_METHOD' => self::PAYMENT_METHOD,
                'CRITERION.SECRET' => 'dfda66284b665952e16b7a29c6363dd0b76a291a6c760ca1591728a00dbdd88881632a61b9'.
                    '35a4c2e30708215d002c67e758ec2704c7bac411240790d71c6afd',
                'CRITERION.SDK_NAME' => 'Heidelpay\\PhpApi',
                'CRITERION.SDK_VERSION' => '17.9.27',
                'FRONTEND.CSS_PATH' => $cssPath,
                'FRONTEND.ENABLED' => 'FALSE',
                'FRONTEND.MODE' => 'WHITELABEL',
                'FRONTEND.PAYMENT_FRAME_ORIGIN' => $paymentFrameOrigin,
                'FRONTEND.PREVENT_ASYNC_REDIRECT' => $preventAsyncRedirect,
                'IDENTIFICATION.SHOPPERID' => $shopperId,
                'IDENTIFICATION.TRANSACTIONID' => $timestamp,
                'NAME.GIVEN' => $firstName,
                'NAME.FAMILY' => $lastName,
                'PAYMENT.CODE' => 'CC.RG',
                'PRESENTATION.AMOUNT' => self::TEST_AMOUNT,
                'PRESENTATION.CURRENCY' => $this->currency,
                'REQUEST.VERSION' => '1.0',
                'SECURITY.SENDER' => $securitySender,
                'TRANSACTION.CHANNEL' => $transactionChannel,
                'TRANSACTION.MODE' => 'CONNECTOR_TEST',
                'USER.LOGIN' => $userLogin,
                'USER.PWD' => $userPassword,
            ];

        $this->assertArraysMatch($parameters, $this->paymentObject->getRequest()->convertToArray());
    }

    /**
     * Verify authorize parameters generated as expected
     *
     * @test
     */
    public function authorizeParametersShouldBeSetUpAsExpected()
    {
        $timestamp = 'CreditCardPaymentMethodTest::authorize 2017-10-27 13:10:40';
        $this->paymentObject->getRequest()->basketData(
            $timestamp,
            self::TEST_AMOUNT,
            $this->currency,
            $this->secret
        );

        $preventAsyncRedirect = 'FALSE';
        $this->paymentObject->authorize(
            self::PAYMENT_FRAME_ORIGIN,
            $preventAsyncRedirect,
            self::CSS_PATH
        );

        /* disable frontend (ifame) and submit the credit card information directly (only for testing) */
        $frontendEnabled = 'FALSE';
        $this->paymentObject->getRequest()->getFrontend()->setEnabled($frontendEnabled);
        $this->paymentObject->getRequest()->getAccount()->setHolder($this->holder);
        $this->paymentObject->getRequest()->getAccount()->setNumber($this->creditCartNumber);
        $this->paymentObject->getRequest()->getAccount()->set('expiry_month', $this->creditCardExpiryMonth);
        $this->paymentObject->getRequest()->getAccount()->set('expiry_year', $this->creditCardExpiryYear);
        $this->paymentObject->getRequest()->getAccount()->setBrand($this->creditCardBrand);
        $this->paymentObject->getRequest()->getAccount()->set('verification', $this->creditCardVerification);

        list($firstName, $lastName, , $shopperId, $street, $state, $zip, $city, $country, $email) =
            $this->customerData->getCustomerDataArray();

        list($securitySender, $userLogin, $userPassword, $transactionChannel, ) =
            $this->authentication->getAuthenticationArray();
        
        $expected = [
            'ACCOUNT.BRAND' => $this->creditCardBrand,
            'ACCOUNT.EXPIRY_MONTH' => $this->creditCardExpiryMonth,
            'ACCOUNT.EXPIRY_YEAR' => $this->creditCardExpiryYear,
            'ACCOUNT.HOLDER' => $this->holder,
            'ACCOUNT.NUMBER' => $this->creditCartNumber,
            'ACCOUNT.VERIFICATION' => $this->creditCardVerification,
            'ADDRESS.CITY' => $city,
            'ADDRESS.COUNTRY' => $country,
            'ADDRESS.STATE' => $state,
            'ADDRESS.STREET' => $street,
            'ADDRESS.ZIP' => $zip,
            'CONTACT.EMAIL' => $email,
            'CRITERION.PAYMENT_METHOD' => self::PAYMENT_METHOD,
            'CRITERION.SECRET' => 'ad9bd03e2d01a9ace6e57d5ca562303e36d1583f03def04fec7dd311c8a19675172f86cfcc0'.
                'f1ace1a840639517bf957dc66a2cce6b61b9e79586a4e8de37eb0',
            'CRITERION.SDK_NAME' => 'Heidelpay\\PhpApi',
            'CRITERION.SDK_VERSION' => '17.9.27',
            'FRONTEND.CSS_PATH' => self::CSS_PATH,
            'FRONTEND.ENABLED' => $frontendEnabled,
            'FRONTEND.MODE' => 'WHITELABEL',
            'FRONTEND.PAYMENT_FRAME_ORIGIN' => self::PAYMENT_FRAME_ORIGIN,
            'FRONTEND.PREVENT_ASYNC_REDIRECT' => $preventAsyncRedirect,
            'IDENTIFICATION.SHOPPERID' => $shopperId,
            'IDENTIFICATION.TRANSACTIONID' => $timestamp,
            'NAME.GIVEN' => $firstName,
            'NAME.FAMILY' => $lastName,
            'PAYMENT.CODE' => 'CC.PA',
            'PRESENTATION.AMOUNT' => self::TEST_AMOUNT,
            'PRESENTATION.CURRENCY' => $this->currency,
            'REQUEST.VERSION' => '1.0',
            'SECURITY.SENDER' => $securitySender,
            'TRANSACTION.CHANNEL' => $transactionChannel,
            'TRANSACTION.MODE' => 'CONNECTOR_TEST',
            'USER.LOGIN' => $userLogin,
            'USER.PWD' => $userPassword,
        ];

        $this->assertArraysMatch($expected, $this->paymentObject->getRequest()->convertToArray());
    }

    /**
     * Verify authorizeOnRegistration parameters generated as expected
     *
     * @test
     */
    public function authorizeOnRegistrationParametersShouldBeSetUpAsExpected()
    {
        $timestamp = 'CreditCardPaymentMethodTest::authorizeOnRegistration 2017-10-27 13:21:13';
        $this->paymentObject->getRequest()->basketData($timestamp, self::TEST_AMOUNT, $this->currency, $this->secret);

        $frontendEnabled = 'FALSE';
        $this->paymentObject->getRequest()->getFrontend()->setEnabled($frontendEnabled);

        $this->paymentObject->authorizeOnRegistration(self::REFERENCE_ID);

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
            'CRITERION.SECRET' => '5d09d0dcb62c70d66c8b13f5dbe603c741be9ece2ed6737ee4d0f0ff2764113f744d835'.
                '8cd423084225efafb4ea299bb3dd7c4d50757095a7f69b17d9146ff92',
            'CRITERION.SDK_NAME' => 'Heidelpay\\PhpApi',
            'CRITERION.SDK_VERSION' => '17.9.27',
            'FRONTEND.ENABLED' => $frontendEnabled,
            'FRONTEND.MODE' => 'WHITELABEL',
            'IDENTIFICATION.SHOPPERID' => $shopperId,
            'IDENTIFICATION.TRANSACTIONID' => $timestamp,
            'IDENTIFICATION.REFERENCEID' => self::REFERENCE_ID,
            'NAME.GIVEN' => $firstName,
            'NAME.FAMILY' => $lastName,
            'PAYMENT.CODE' => 'CC.PA',
            'PRESENTATION.AMOUNT' => self::TEST_AMOUNT,
            'PRESENTATION.CURRENCY' => $this->currency,
            'REQUEST.VERSION' => '1.0',
            'SECURITY.SENDER' => $securitySender,
            'TRANSACTION.CHANNEL' => $transactionChannel,
            'TRANSACTION.MODE' => 'CONNECTOR_TEST',
            'USER.LOGIN' => $userLogin,
            'USER.PWD' => $userPassword,
        ];

        $this->assertArraysMatch($expected, $this->paymentObject->getRequest()->convertToArray());
    }

    /**
     * Verify debit parameters generated as expected
     *
     * @test
     */
    public function debitParametersShouldBeSetUpAsExpected()
    {
        $timestamp = 'CreditCardPaymentMethodTest::debit 2017-10-27 13:24:08';
        $this->paymentObject->getRequest()->basketData($timestamp, self::TEST_AMOUNT, $this->currency, $this->secret);

        $preventAsyncRedirect = 'FALSE';
        $this->paymentObject->debit(
            self::PAYMENT_FRAME_ORIGIN,
            $preventAsyncRedirect,
            self::CSS_PATH
        );

        /* disable frontend (ifame) and submit the credit card information directly (only for testing) */
        $frontendEnabled = 'FALSE';
        $this->paymentObject->getRequest()->getFrontend()->setEnabled($frontendEnabled);
        $this->paymentObject->getRequest()->getAccount()->setHolder($this->holder);
        $this->paymentObject->getRequest()->getAccount()->setNumber($this->creditCartNumber);
        $this->paymentObject->getRequest()->getAccount()->set('expiry_month', $this->creditCardExpiryMonth);
        $this->paymentObject->getRequest()->getAccount()->set('expiry_year', $this->creditCardExpiryYear);
        $this->paymentObject->getRequest()->getAccount()->setBrand($this->creditCardBrand);
        $this->paymentObject->getRequest()->getAccount()->set('verification', $this->creditCardVerification);

        list($firstName, $lastName, , $shopperId, $street, $state, $zip, $city, $country, $email) =
            $this->customerData->getCustomerDataArray();

        list($securitySender, $userLogin, $userPassword, $transactionChannel, ) =
            $this->authentication->getAuthenticationArray();

        $expected = [
            'ACCOUNT.BRAND' => $this->creditCardBrand,
            'ACCOUNT.EXPIRY_MONTH' => $this->creditCardExpiryMonth,
            'ACCOUNT.EXPIRY_YEAR' => $this->creditCardExpiryYear,
            'ACCOUNT.HOLDER' => $this->holder,
            'ACCOUNT.NUMBER' => $this->creditCartNumber,
            'ACCOUNT.VERIFICATION' => $this->creditCardVerification,
            'ADDRESS.CITY' => $city,
            'ADDRESS.COUNTRY' => $country,
            'ADDRESS.STATE' => $state,
            'ADDRESS.STREET' => $street,
            'ADDRESS.ZIP' => $zip,
            'CONTACT.EMAIL' => $email,
            'CRITERION.PAYMENT_METHOD' => self::PAYMENT_METHOD,
            'CRITERION.SECRET' => '5cf507a4837cb354cbd3f8fc45af8977d6bfc799ff2217eb0c58203dfade9ae933c'.
                '5675988606f26cb247a7ac66ec8e798cdc2a72135100e0330ebecb6029e92',
            'CRITERION.SDK_NAME' => 'Heidelpay\\PhpApi',
            'CRITERION.SDK_VERSION' => '17.9.27',
            'FRONTEND.CSS_PATH' => self::CSS_PATH,
            'FRONTEND.ENABLED' => $frontendEnabled,
            'FRONTEND.MODE' => 'WHITELABEL',
            'FRONTEND.PAYMENT_FRAME_ORIGIN' => self::PAYMENT_FRAME_ORIGIN,
            'FRONTEND.PREVENT_ASYNC_REDIRECT' => $preventAsyncRedirect,
            'IDENTIFICATION.SHOPPERID' => $shopperId,
            'IDENTIFICATION.TRANSACTIONID' => $timestamp,
            'NAME.GIVEN' => $firstName,
            'NAME.FAMILY' => $lastName,
            'PAYMENT.CODE' => 'CC.DB',
            'PRESENTATION.AMOUNT' => self::TEST_AMOUNT,
            'PRESENTATION.CURRENCY' => $this->currency,
            'REQUEST.VERSION' => '1.0',
            'SECURITY.SENDER' => $securitySender,
            'TRANSACTION.CHANNEL' => $transactionChannel,
            'TRANSACTION.MODE' => 'CONNECTOR_TEST',
            'USER.LOGIN' => $userLogin,
            'USER.PWD' => $userPassword,
        ];

        $this->assertArraysMatch($expected, $this->paymentObject->getRequest()->convertToArray());
    }

    /**
     * Verify debitOnRegistration parameters generated as expected
     *
     * @test
     */
    public function debitOnRegistrationParametersShouldBeSetUpAsExpected()
    {
        $timestamp = 'CreditCardPaymentMethodTest::debitOnRegistration 2017-10-27 13:28:12';
        $this->paymentObject->getRequest()->basketData(
            $timestamp,
            self::TEST_AMOUNT,
            $this->currency,
            $this->secret
        );

        $frontendEnabled = 'FALSE';
        $this->paymentObject->getRequest()->getFrontend()->setEnabled($frontendEnabled);

        $this->paymentObject->debitOnRegistration(self::REFERENCE_ID);

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
            'CRITERION.SECRET' => '7f9355256d488f609f50048ff675ee75db238b58ed7d7bbbfc572fa4669ce9c74e7a8b5'.
                '940d80177f3761197b335c863bf42d44c82f0cfef04b482caee97bae0',
            'CRITERION.SDK_NAME' => 'Heidelpay\\PhpApi',
            'CRITERION.SDK_VERSION' => '17.9.27',
            'FRONTEND.ENABLED' => $frontendEnabled,
            'FRONTEND.MODE' => 'WHITELABEL',
            'IDENTIFICATION.SHOPPERID' => $shopperId,
            'IDENTIFICATION.TRANSACTIONID' => $timestamp,
            'IDENTIFICATION.REFERENCEID' => self::REFERENCE_ID,
            'NAME.GIVEN' => $firstName,
            'NAME.FAMILY' => $lastName,
            'PAYMENT.CODE' => 'CC.DB',
            'PRESENTATION.AMOUNT' => self::TEST_AMOUNT,
            'PRESENTATION.CURRENCY' => $this->currency,
            'REQUEST.VERSION' => '1.0',
            'SECURITY.SENDER' => $securitySender,
            'TRANSACTION.CHANNEL' => $transactionChannel,
            'TRANSACTION.MODE' => 'CONNECTOR_TEST',
            'USER.LOGIN' => $userLogin,
            'USER.PWD' => $userPassword,
        ];

        $this->assertArraysMatch($expected, $this->paymentObject->getRequest()->convertToArray());
    }

    /**
     * Verify refund parameters generated as expected
     *
     * @test
     */
    public function refundParametersShouldBeSetUpAsExpected()
    {
        $timestamp = 'CreditCardPaymentMethodTest::refund 2017-10-27 13:31:49';
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
            'CRITERION.SECRET' => '29eadadb05e271a669bf9db86aa3065ac028ddd25a08156804459eb9afcbda80e99442a1'.
                '6abea81287a9a19205471372a372962811f66fc62ea3b4c1c631f33a',
            'CRITERION.SDK_NAME' => 'Heidelpay\\PhpApi',
            'CRITERION.SDK_VERSION' => '17.9.27',
            'FRONTEND.ENABLED' => 'FALSE',
            'FRONTEND.MODE' => 'WHITELABEL',
            'IDENTIFICATION.SHOPPERID' => $shopperId,
            'IDENTIFICATION.TRANSACTIONID' => $timestamp,
            'IDENTIFICATION.REFERENCEID' => self::REFERENCE_ID,
            'NAME.GIVEN' => $firstName,
            'NAME.FAMILY' => $lastName,
            'PAYMENT.CODE' => 'CC.RF',
            'PRESENTATION.AMOUNT' => self::TEST_AMOUNT,
            'PRESENTATION.CURRENCY' => $this->currency,
            'REQUEST.VERSION' => '1.0',
            'SECURITY.SENDER' => $securitySender,
            'TRANSACTION.CHANNEL' => $transactionChannel,
            'TRANSACTION.MODE' => 'CONNECTOR_TEST',
            'USER.LOGIN' => $userLogin,
            'USER.PWD' => $userPassword,
        ];

        $this->assertArraysMatch($expected, $this->paymentObject->getRequest()->convertToArray());
    }

    /**
     * Verify reversal parameters generated as expected
     *
     * @test
     */
    public function reversalParametersShouldBeSetUpAsExpected()
    {
        $timestamp = 'CreditCardPaymentMethodTest::reversal 2017-10-27 15:12:11';
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
            'CRITERION.SECRET' => '6e34a4fcb0fc1e3559d1430eab421154813ade8309a9ec034b0fafc19ddd38ccc3d'.
            'd58946b13bac3ec995489487b49ce6222c435c1c3538ba3fa6c70657b872f',
            'CRITERION.SDK_NAME' => 'Heidelpay\\PhpApi',
            'CRITERION.SDK_VERSION' => '17.9.27',
            'FRONTEND.ENABLED' => 'FALSE',
            'FRONTEND.MODE' => 'WHITELABEL',
            'IDENTIFICATION.SHOPPERID' => $shopperId,
            'IDENTIFICATION.TRANSACTIONID' => $timestamp,
            'IDENTIFICATION.REFERENCEID' => self::REFERENCE_ID,
            'NAME.GIVEN' => $firstName,
            'NAME.FAMILY' => $lastName,
            'PAYMENT.CODE' => 'CC.RV',
            'PRESENTATION.AMOUNT' => self::TEST_AMOUNT,
            'PRESENTATION.CURRENCY' => $this->currency,
            'REQUEST.VERSION' => '1.0',
            'SECURITY.SENDER' => $securitySender,
            'TRANSACTION.CHANNEL' => $transactionChannel,
            'TRANSACTION.MODE' => 'CONNECTOR_TEST',
            'USER.LOGIN' => $userLogin,
            'USER.PWD' => $userPassword
        ];

        $this->assertArraysMatch($expected, $this->paymentObject->getRequest()->convertToArray());
    }

    //</editor-fold>
}
