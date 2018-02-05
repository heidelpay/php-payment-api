<?php

namespace Heidelpay\Tests\PhpPaymentApi\Unit\PaymentMethods;

use AspectMock\Proxy\InstanceProxy;
use AspectMock\Test as test;
use Heidelpay\PhpPaymentApi\Constants\ApiConfig;
use Heidelpay\PhpPaymentApi\PaymentMethods\DirectDebitB2CSecuredPaymentMethod;
use Heidelpay\Tests\PhpPaymentApi\Helper\BasePaymentMethodTest;

/**
 * This test class verifies the behaviour in all transaction represented by one payment method.
 * There is no actual communication to the server since the curl adapter is being mocked.
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author  Simon Gabriel
 *
 * @package heidelpay\php-payment-api\tests\unit
 */
class PaymentMethodTransactionTest extends BasePaymentMethodTest
{
    const PAYMENT_METHOD_SHORT = 'DD';
    const CUSTOMER_BIRTHDAY = '1982-07-12';
    const CUSTOMER_SALUTATION = 'MR';

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

        $directDebitSecured = new DirectDebitB2CSecuredPaymentMethod();
        $directDebitSecured->getRequest()->authentification(...$authentication);
        $directDebitSecured->getRequest()->customerAddress(...$customerDetails);
        $directDebitSecured->getRequest()->b2cSecured(self::CUSTOMER_SALUTATION, self::CUSTOMER_BIRTHDAY);
        $directDebitSecured->dryRun = false;

        $this->paymentObject = $directDebitSecured;

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
            ['debit', null,'DB'],
            ['registration', null,'RG'],
            ['authorizeOnRegistration', null,'PA'],
            ['debitOnRegistration', null,'DB'],
            ['refund', null,'RF'],
            ['reversal', null,'RV'],
            ['rebill', null,'RB'],
            ['capture', null,'CP']
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
        $adapter = $this->getAdapterMock();
        $adapter->verifyInvokedOnce('sendPost');
    }

    //</editor-fold>

    /**
     * Verify registration parameters generated as expected
     *
     * @test
     */
    public function registrationParametersShouldBeSetUpAsExpected()
    {
        $timestamp = 'DirectDebitB2CSecuredPaymentMethodTest::registration 2017-11-01 10:33:12';
        $this->paymentObject->getRequest()->basketData(
            $timestamp,
            self::TEST_AMOUNT,
            $this->currency,
            $this->secret
        );
        $this->paymentObject->getRequest()->async('DE', 'https://dev.heidelpay.de');

        /* disable frontend (iframe) and submit the card information directly (only for testing) */
        $frontendEnabled = 'FALSE';
        $this->paymentObject->getRequest()->getFrontend()->setEnabled($frontendEnabled);
        $this->paymentObject->getRequest()->getAccount()->setHolder($this->holder);
        $this->paymentObject->getRequest()->getAccount()->setIban($this->iban);

        $this->paymentObject->registration();

        list($firstName, $lastName, , $shopperId, $street, $state, $zip, $city, $country, $email) =
            $this->customerData->getCustomerDataArray();

        list($securitySender, $userLogin, $userPassword, $transactionChannel, ) =
            $this->authentication->getAuthenticationArray();

        // this is done to avoid syntax warnings
        $object = $this->paymentObject;
        
        $expected =
            [
                'ACCOUNT.HOLDER' => $this->holder,
                'ACCOUNT.IBAN' => $this->iban,
                'ADDRESS.CITY' => $city,
                'ADDRESS.COUNTRY' => $country,
                'ADDRESS.STATE' => $state,
                'ADDRESS.STREET' => $street,
                'ADDRESS.ZIP' => $zip,
                'CONTACT.EMAIL' => $email,
                'CRITERION.PAYMENT_METHOD' => $object::getClassName(),
                'CRITERION.SECRET' => '1ce9f65da579ce18dce622dd5dfeca2e87a379f52451202ef58ebdfac1e15b4' .
                    '5e649b0cb7466e040011f3f76e056b2ce83561063815ef935cbb0c6b421cc3317',
                'CRITERION.SDK_NAME' => 'Heidelpay\\PhpPaymentApi',
                'CRITERION.SDK_VERSION' => ApiConfig::SDK_VERSION,
                'FRONTEND.ENABLED' => 'FALSE',
                'FRONTEND.MODE' => 'WHITELABEL',
                'FRONTEND.LANGUAGE' => 'DE',
                'FRONTEND.RESPONSE_URL' => self::REDIRECT_URL,
                'IDENTIFICATION.SHOPPERID' => $shopperId,
                'IDENTIFICATION.TRANSACTIONID' => $timestamp,
                'NAME.SALUTATION' => self::CUSTOMER_SALUTATION,
                'NAME.GIVEN' => $firstName,
                'NAME.FAMILY' => $lastName,
                'NAME.BIRTHDATE' => self::CUSTOMER_BIRTHDAY,
                'PAYMENT.CODE' => self::PAYMENT_METHOD_SHORT . '.RG',
                'PRESENTATION.AMOUNT' => self::TEST_AMOUNT,
                'PRESENTATION.CURRENCY' => $this->currency,
                'REQUEST.VERSION' => '1.0',
                'SECURITY.SENDER' => $securitySender,
                'TRANSACTION.CHANNEL' => $transactionChannel,
                'TRANSACTION.MODE' => 'CONNECTOR_TEST',
                'USER.LOGIN' => $userLogin,
                'USER.PWD' => $userPassword,
            ];

        $this->assertThat($this->paymentObject->getRequest()->toArray(), $this->arraysMatchExactly($expected));
    }

    /**
     * Verify authorize parameters generated as expected
     *
     * @test
     */
    public function authorizeParametersShouldBeSetUpAsExpected()
    {
        $timestamp = 'DirectDebitB2CSecuredPaymentMethodTest::authorize 2017-11-01 10:52:48';
        $this->paymentObject->getRequest()->basketData(
            $timestamp,
            self::TEST_AMOUNT,
            $this->currency,
            $this->secret
        );
        $this->paymentObject->getRequest()->async('DE', 'https://dev.heidelpay.de');

        /* disable frontend (ifame) and submit the card information directly (only for testing) */
        $frontendEnabled = 'FALSE';
        $this->paymentObject->getRequest()->getFrontend()->setEnabled($frontendEnabled);
        $this->paymentObject->getRequest()->getAccount()->setHolder($this->holder);
        $this->paymentObject->getRequest()->getAccount()->setIban($this->iban);

        $this->paymentObject->authorize();

        list($firstName, $lastName, , $shopperId, $street, $state, $zip, $city, $country, $email) =
            $this->customerData->getCustomerDataArray();

        list($securitySender, $userLogin, $userPassword, $transactionChannel, ) =
            $this->authentication->getAuthenticationArray();

        // this is done to avoid syntax warnings
        $object = $this->paymentObject;

        $expected = [
            'ACCOUNT.IBAN' => $this->iban,
            'ACCOUNT.HOLDER' => $this->holder,
            'ADDRESS.CITY' => $city,
            'ADDRESS.COUNTRY' => $country,
            'ADDRESS.STATE' => $state,
            'ADDRESS.STREET' => $street,
            'ADDRESS.ZIP' => $zip,
            'CONTACT.EMAIL' => $email,
            'CRITERION.PAYMENT_METHOD' => $object::getClassName(),
            'CRITERION.SECRET' => 'e03d34e875cf7c5fd97e3a207cc5d54007509585475e0359cbcb0678c874bca9a1' .
                '9e10c4cc11d5ee1e7bda3e46c85008bfc38955621301c25d00adcabd2d20a8',
            'CRITERION.SDK_NAME' => 'Heidelpay\PhpPaymentApi',
            'CRITERION.SDK_VERSION' => ApiConfig::SDK_VERSION,
            'FRONTEND.ENABLED' => $frontendEnabled,
            'FRONTEND.MODE' => 'WHITELABEL',
            'FRONTEND.RESPONSE_URL' => self::REDIRECT_URL,
            'FRONTEND.LANGUAGE' => 'DE',
            'IDENTIFICATION.SHOPPERID' => $shopperId,
            'IDENTIFICATION.TRANSACTIONID' => $timestamp,
            'NAME.BIRTHDATE' => self::CUSTOMER_BIRTHDAY,
            'NAME.SALUTATION' => self::CUSTOMER_SALUTATION,
            'NAME.GIVEN' => $firstName,
            'NAME.FAMILY' => $lastName,
            'PAYMENT.CODE' => self::PAYMENT_METHOD_SHORT . '.PA',
            'PRESENTATION.AMOUNT' => self::TEST_AMOUNT,
            'PRESENTATION.CURRENCY' => $this->currency,
            'REQUEST.VERSION' => '1.0',
            'SECURITY.SENDER' => $securitySender,
            'TRANSACTION.CHANNEL' => $transactionChannel,
            'TRANSACTION.MODE' => 'CONNECTOR_TEST',
            'USER.LOGIN' => $userLogin,
            'USER.PWD' => $userPassword,
        ];

        $this->assertThat($this->paymentObject->getRequest()->toArray(), $this->arraysMatchExactly($expected));
    }

    /**
     * Verify authorizeOnRegistration parameters generated as expected
     *
     * @test
     */
    public function authorizeOnRegistrationParametersShouldBeSetUpAsExpected()
    {
        $timestamp = 'DirectDebitB2CSecuredPaymentMethodTest::authorizeOnRegistration 2017-11-01 12:26:41';
        $this->paymentObject->getRequest()->basketData($timestamp, self::TEST_AMOUNT, $this->currency, $this->secret);

        $frontendEnabled = 'FALSE';
        $this->paymentObject->getRequest()->getFrontend()->setEnabled($frontendEnabled);

        $this->paymentObject->authorizeOnRegistration(self::REFERENCE_ID);

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
            'CRITERION.SECRET' => '6a98064cd9fe2b1044c8e0ef339a3b6956d700854c5fd67165a1fcf9e9b845c9b2' .
                '0a2f68a8dde6bd783d04350ca4d349eb10f4bbebb1ae37cb621b48aca67c59',
            'CRITERION.SDK_NAME' => 'Heidelpay\\PhpPaymentApi',
            'CRITERION.SDK_VERSION' => ApiConfig::SDK_VERSION,
            'FRONTEND.ENABLED' => $frontendEnabled,
            'FRONTEND.MODE' => 'WHITELABEL',
            'IDENTIFICATION.SHOPPERID' => $shopperId,
            'IDENTIFICATION.TRANSACTIONID' => $timestamp,
            'IDENTIFICATION.REFERENCEID' => self::REFERENCE_ID,
            'NAME.BIRTHDATE' => self::CUSTOMER_BIRTHDAY,
            'NAME.SALUTATION' => self::CUSTOMER_SALUTATION,
            'NAME.GIVEN' => $firstName,
            'NAME.FAMILY' => $lastName,
            'PAYMENT.CODE' => self::PAYMENT_METHOD_SHORT . '.PA',
            'PRESENTATION.AMOUNT' => self::TEST_AMOUNT,
            'PRESENTATION.CURRENCY' => $this->currency,
            'REQUEST.VERSION' => '1.0',
            'SECURITY.SENDER' => $securitySender,
            'TRANSACTION.CHANNEL' => $transactionChannel,
            'TRANSACTION.MODE' => 'CONNECTOR_TEST',
            'USER.LOGIN' => $userLogin,
            'USER.PWD' => $userPassword,
        ];

        $this->assertThat($this->paymentObject->getRequest()->toArray(), $this->arraysMatchExactly($expected));
    }

    /**
     * Verify debit parameters generated as expected
     *
     * @test
     */
    public function debitParametersShouldBeSetUpAsExpected()
    {
        $timestamp = 'DirectDebitB2CSecuredPaymentMethodTest::debit 2017-11-01 12:33:14';
        $this->paymentObject->getRequest()->basketData($timestamp, self::TEST_AMOUNT, $this->currency, $this->secret);
        $this->paymentObject->getRequest()->async('DE', 'https://dev.heidelpay.de');

        $frontendEnabled = 'FALSE';
        $this->paymentObject->getRequest()->getFrontend()->setEnabled($frontendEnabled);
        $this->paymentObject->getRequest()->getAccount()->setHolder($this->holder);
        $this->paymentObject->getRequest()->getAccount()->setIban($this->iban);

        $this->paymentObject->debit();

        list($firstName, $lastName, , $shopperId, $street, $state, $zip, $city, $country, $email) =
            $this->customerData->getCustomerDataArray();

        list($securitySender, $userLogin, $userPassword, $transactionChannel, ) =
            $this->authentication->getAuthenticationArray();

        // this is done to avoid syntax warnings
        $object = $this->paymentObject;
        
        $expected = [
            'ACCOUNT.HOLDER' => $this->holder,
            'ACCOUNT.IBAN' => $this->iban,
            'ADDRESS.CITY' => $city,
            'ADDRESS.COUNTRY' => $country,
            'ADDRESS.STATE' => $state,
            'ADDRESS.STREET' => $street,
            'ADDRESS.ZIP' => $zip,
            'CONTACT.EMAIL' => $email,
            'CRITERION.PAYMENT_METHOD' => $object::getClassName(),
            'CRITERION.SECRET' => '9cd425b6a0d82057f1cedbed87c2a6d9fdcc720a72192bc333bffe67965e503cd1b6' .
                '004f7c5551ecf9f55615b372e8d775ce9aae85cb94ba480653a5c1f4c3ae',
            'CRITERION.SDK_NAME' => 'Heidelpay\\PhpPaymentApi',
            'CRITERION.SDK_VERSION' => ApiConfig::SDK_VERSION,
            'FRONTEND.ENABLED' => $frontendEnabled,
            'FRONTEND.MODE' => 'WHITELABEL',
            'FRONTEND.RESPONSE_URL' => self::REDIRECT_URL,
            'FRONTEND.LANGUAGE' => 'DE',
            'IDENTIFICATION.SHOPPERID' => $shopperId,
            'IDENTIFICATION.TRANSACTIONID' => $timestamp,
            'NAME.BIRTHDATE' => self::CUSTOMER_BIRTHDAY,
            'NAME.SALUTATION' => self::CUSTOMER_SALUTATION,
            'NAME.GIVEN' => $firstName,
            'NAME.FAMILY' => $lastName,
            'PAYMENT.CODE' => self::PAYMENT_METHOD_SHORT . '.DB',
            'PRESENTATION.AMOUNT' => self::TEST_AMOUNT,
            'PRESENTATION.CURRENCY' => $this->currency,
            'REQUEST.VERSION' => '1.0',
            'SECURITY.SENDER' => $securitySender,
            'TRANSACTION.CHANNEL' => $transactionChannel,
            'TRANSACTION.MODE' => 'CONNECTOR_TEST',
            'USER.LOGIN' => $userLogin,
            'USER.PWD' => $userPassword,
        ];

        $this->assertThat($this->paymentObject->getRequest()->toArray(), $this->arraysMatchExactly($expected));
    }

    /**
     * Verify debitOnRegistration parameters generated as expected
     *
     * @test
     */
    public function debitOnRegistrationParametersShouldBeSetUpAsExpected()
    {
        $timestamp = 'DirectDebitB2CSecuredPaymentMethodTest::debitOnRegistration 2017-11-01 13:09:20';
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
            'CRITERION.SECRET' => '40e583afeffc94547183c736e6219ceffbc1425a99695742f22d71cdd06475f34550cfb2' .
                'a145a39e72ee818385ade0adb8d571da3c0fa6e094fd43c900192166',
            'CRITERION.SDK_NAME' => 'Heidelpay\\PhpPaymentApi',
            'CRITERION.SDK_VERSION' => ApiConfig::SDK_VERSION,
            'FRONTEND.ENABLED' => $frontendEnabled,
            'FRONTEND.MODE' => 'WHITELABEL',
            'IDENTIFICATION.SHOPPERID' => $shopperId,
            'IDENTIFICATION.TRANSACTIONID' => $timestamp,
            'IDENTIFICATION.REFERENCEID' => self::REFERENCE_ID,
            'NAME.BIRTHDATE' => self::CUSTOMER_BIRTHDAY,
            'NAME.SALUTATION' => self::CUSTOMER_SALUTATION,
            'NAME.GIVEN' => $firstName,
            'NAME.FAMILY' => $lastName,
            'PAYMENT.CODE' => self::PAYMENT_METHOD_SHORT . '.DB',
            'PRESENTATION.AMOUNT' => self::TEST_AMOUNT,
            'PRESENTATION.CURRENCY' => $this->currency,
            'REQUEST.VERSION' => '1.0',
            'SECURITY.SENDER' => $securitySender,
            'TRANSACTION.CHANNEL' => $transactionChannel,
            'TRANSACTION.MODE' => 'CONNECTOR_TEST',
            'USER.LOGIN' => $userLogin,
            'USER.PWD' => $userPassword,
        ];

        $this->assertThat($this->paymentObject->getRequest()->toArray(), $this->arraysMatchExactly($expected));
    }

    /**
     * Verify refund parameters generated as expected
     *
     * @test
     */
    public function refundParametersShouldBeSetUpAsExpected()
    {
        $timestamp = 'DirectDebitB2CSecuredPaymentMethodTest::refund 2017-11-01 12:39:45';
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
            'CRITERION.SECRET' => 'e2cf8f7086cad3dc71972f193d6a5a230d3dd00e13d20ad876ba2feae868bf6b79c4564' .
                '0f88576ed39546dc68e15f87eaf7557618426d904245b745cdc65c881',
            'CRITERION.SDK_NAME' => 'Heidelpay\\PhpPaymentApi',
            'CRITERION.SDK_VERSION' => ApiConfig::SDK_VERSION,
            'FRONTEND.MODE' => 'WHITELABEL',
            'FRONTEND.ENABLED' => 'FALSE',
            'IDENTIFICATION.SHOPPERID' => $shopperId,
            'IDENTIFICATION.TRANSACTIONID' => $timestamp,
            'IDENTIFICATION.REFERENCEID' => self::REFERENCE_ID,
            'NAME.GIVEN' => $firstName,
            'NAME.FAMILY' => $lastName,
            'NAME.BIRTHDATE' => self::CUSTOMER_BIRTHDAY,
            'NAME.SALUTATION' => self::CUSTOMER_SALUTATION,
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

        $this->assertThat($this->paymentObject->getRequest()->toArray(), $this->arraysMatchExactly($expected));
    }

    /**
     * Verify reversal parameters generated as expected
     *
     * @test
     */
    public function reversalParametersShouldBeSetUpAsExpected()
    {
        $timestamp = 'DirectDebitB2CSecuredPaymentMethodTest::reversal 2017-11-01 12:45:57';
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
            'CRITERION.SECRET' => '716703bf5cc18f83b3231474f773a69ad98e2f477c2abe0b5b904e02d232432b7ce90' .
                '20da68fb0a77464b94fd26d71cceab58d36a0b378759054fabf8a65a7aa',
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

        $this->assertThat($this->paymentObject->getRequest()->toArray(), $this->arraysMatchExactly($expected));
    }

    /**
     * Verify rebill parameters generated as expected
     *
     * @test
     */
    public function rebillParametersShouldBeSetUpAsExpected()
    {
        $timestamp = 'DirectDebitB2CSecuredPaymentMethodTest::rebill 2017-11-01 12:48:59';
        $this->paymentObject->getRequest()->basketData(
            $timestamp,
            self::TEST_AMOUNT,
            $this->currency,
            $this->secret
        );

        $this->paymentObject->rebill(self::REFERENCE_ID);

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
            'CRITERION.SECRET' => '59718aa5021d53aa936ec7d516791a94cec0eaf799d854105b40fe97fbb5fc8f6ca40be4' .
                '999f057d0eda9316c18d26ecbcca684ac143b134cf19135215ee0d73',
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
            'PAYMENT.CODE' => self::PAYMENT_METHOD_SHORT . '.RB',
            'PRESENTATION.AMOUNT' => self::TEST_AMOUNT,
            'PRESENTATION.CURRENCY' => $this->currency,
            'REQUEST.VERSION' => '1.0',
            'SECURITY.SENDER' => $securitySender,
            'TRANSACTION.CHANNEL' => $transactionChannel,
            'TRANSACTION.MODE' => 'CONNECTOR_TEST',
            'USER.LOGIN' => $userLogin,
            'USER.PWD' => $userPassword,
        ];

        $this->assertThat($this->paymentObject->getRequest()->toArray(), $this->arraysMatchExactly($expected));
    }

    /**
     * Verify capture parameters generated as expected
     *
     * @test
     */
    public function captureParametersShouldBeSetUpAsExpected()
    {
        $timestamp = 'DirectDebitB2CSecuredPaymentMethodTest::capture 2017-11-01 12:51:18';
        $this->paymentObject->getRequest()->basketData(
            $timestamp,
            self::TEST_AMOUNT,
            $this->currency,
            $this->secret
        );

        $this->paymentObject->capture(self::REFERENCE_ID);

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
            'CRITERION.SECRET' => '235f76675fb775e3a931608125c9ed4d404a8aa5f28b76019d7a1816cd533a3d47fc05ef1f' .
                'f0f84cef09b3fc92374c64018a05e08827684c54fd0c309373ba2e',
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
            'PAYMENT.CODE' => self::PAYMENT_METHOD_SHORT . '.CP',
            'PRESENTATION.AMOUNT' => self::TEST_AMOUNT,
            'PRESENTATION.CURRENCY' => $this->currency,
            'REQUEST.VERSION' => '1.0',
            'SECURITY.SENDER' => $securitySender,
            'TRANSACTION.CHANNEL' => $transactionChannel,
            'TRANSACTION.MODE' => 'CONNECTOR_TEST',
            'USER.LOGIN' => $userLogin,
            'USER.PWD' => $userPassword,
        ];

        $this->assertThat($this->paymentObject->getRequest()->toArray(), $this->arraysMatchExactly($expected));
    }

    /**
     * Verify finalize parameters generated as expected
     *
     * @test
     */
    public function finalizeParametersShouldBeSetUpAsExpected()
    {
        $timestamp = 'DirectDebitB2CSecuredPaymentMethodTest::finalize 2017-11-02 09:05:42';
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
            'CRITERION.SECRET' => '665cfc0c6452721445c66e6017566f303d0a557ff857348c8f65f6a73f042d1f2824470' .
                'ef9ae56dabe050f0078c5abcebe2ca6d42612c400fa9c3f58ad219853',
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
            'PAYMENT.CODE' => self::PAYMENT_METHOD_SHORT . '.FI',
            'PRESENTATION.AMOUNT' => self::TEST_AMOUNT,
            'PRESENTATION.CURRENCY' => $this->currency,
            'REQUEST.VERSION' => '1.0',
            'SECURITY.SENDER' => $securitySender,
            'TRANSACTION.CHANNEL' => $transactionChannel,
            'TRANSACTION.MODE' => 'CONNECTOR_TEST',
            'USER.LOGIN' => $userLogin,
            'USER.PWD' => $userPassword,
        ];

        $this->assertThat($this->paymentObject->getRequest()->toArray(), $this->arraysMatchExactly($expected));
    }

    //</editor-fold>
}
