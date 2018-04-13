<?php

namespace Heidelpay\Tests\PhpPaymentApi\Unit\PaymentMethods;

use AspectMock\Test as test;
use Heidelpay\PhpPaymentApi\Constants\ApiConfig;
use Heidelpay\PhpPaymentApi\Constants\PaymentMethod;
use Heidelpay\PhpPaymentApi\PaymentMethods\CreditCardPaymentMethod;
use Heidelpay\Tests\PhpPaymentApi\Helper\BasePaymentMethodTest;
use Heidelpay\PhpPaymentApi\Constants\TransactionType;
use Heidelpay\PhpPaymentApi\Constants\TransactionMode;

/**
 * This test class verifies the special functionality of the CreditCardPaymentMethod not covered in
 * GenericPaymentMethodTest and PaymentMethodTransactionTest.
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
class CreditCardPaymentMethodTest extends BasePaymentMethodTest
{
    const PAYMENT_METHOD_SHORT = PaymentMethod::CREDIT_CARD;

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
     * Card number
     * Do not use real card information for this test. For more details read the information
     * on top of this test class.
     *
     * @var string $cartNumber
     */
    protected $cartNumber = '4711100000000000';

    /**
     * Card brand
     * Do not use real card information for this test. For more details read the information
     * on top of this test class.
     *
     * @var string $cardBrand
     */
    protected $cardBrand = 'VISA';

    /**
     * Card verification
     * Do not use real card information for this test. For more details read the information
     * on top of this test class.
     *
     * @var string $cardVerification
     */
    protected $cardVerification = '123';

    /**
     * Card expiry month
     *
     * @var string $cardExpiryMonth
     */
    protected $cardExpiryMonth = '04';

    /**
     * Card expiry year
     *
     * @var string $cardExpiryYear
     */
    protected $cardExpiryYear = '2040';

    /**
     * PaymentObject
     *
     * @var CreditCardPaymentMethod $paymentObject
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
        $this->authentication->setTransactionChannel('31HA07BC8142C5A171744F3D6D155865');

        $paymentObject = new CreditCardPaymentMethod();
        $paymentObject->getRequest()->authentification(...$this->authentication->getAuthenticationArray());
        $paymentObject->getRequest()->customerAddress(...$this->customerData->getCustomerDataArray());
        $paymentObject->dryRun = false;

        $this->paymentObject = $paymentObject;

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

    //<editor-fold desc="Tests">

    /**
     * Verify registration parameters generated as expected
     *
     * @test
     *
     * @throws \Exception
     */
    public function registrationParametersShouldBeSetUpAsExpected()
    {
        $timestamp = 'CreditCardPaymentMethodTest::registrationParametersShouldBeSetUpAsExpected 2017-10-26 15:41:12';
        $this->paymentObject->getRequest()->basketData($timestamp, self::TEST_AMOUNT, $this->currency, $this->secret);

        $preventAsyncRedirect = 'FALSE';
        $this->paymentObject->registration(self::PAYMENT_FRAME_ORIGIN, $preventAsyncRedirect, self::CSS_PATH);

        /* disable frontend (iframe) and submit the card information directly (only for testing) */
        $frontendEnabled = 'FALSE';
        $this->paymentObject->getRequest()->getFrontend()->setEnabled($frontendEnabled);
        $this->paymentObject->getRequest()->getAccount()->setHolder($this->holder);
        $this->paymentObject->getRequest()->getAccount()->setNumber($this->cartNumber);
        $this->paymentObject->getRequest()->getAccount()->setExpiryMonth($this->cardExpiryMonth);
        $this->paymentObject->getRequest()->getAccount()->setExpiryYear($this->cardExpiryYear);
        $this->paymentObject->getRequest()->getAccount()->setBrand($this->cardBrand);
        $this->paymentObject->getRequest()->getAccount()->setVerification($this->cardVerification);

        list($firstName, $lastName, , $shopperId, $street, $state, $zip, $city, $country, $email) =
            $this->customerData->getCustomerDataArray();

        list($securitySender, $userLogin, $userPassword, $transactionChannel, ) =
            $this->authentication->getAuthenticationArray();

        // this is done to avoid syntax warnings
        $object = $this->paymentObject;

        $expected =
            [
                'ACCOUNT.BRAND' => $this->cardBrand,
                'ACCOUNT.EXPIRY_MONTH' => $this->cardExpiryMonth,
                'ACCOUNT.EXPIRY_YEAR' => $this->cardExpiryYear,
                'ACCOUNT.HOLDER' => $this->holder,
                'ACCOUNT.NUMBER' => $this->cartNumber,
                'ACCOUNT.VERIFICATION' => $this->cardVerification,
                'ADDRESS.CITY' => $city,
                'ADDRESS.COUNTRY' => $country,
                'ADDRESS.STATE' => $state,
                'ADDRESS.STREET' => $street,
                'ADDRESS.ZIP' => $zip,
                'CONTACT.EMAIL' => $email,
                'CRITERION.PAYMENT_METHOD' => $object::getClassName(),
                'CRITERION.SECRET' => 'b2741166ff6bbb45bca9dd74b4dbdfbc9bc92b13d943bdf8c0d557fb42fa6b475cff230' .
                    'c930068cddb18f384fda940a2f8831782e2c2559544319dee0f33a237',
                'CRITERION.SDK_NAME' => 'Heidelpay\\PhpPaymentApi',
                'CRITERION.SDK_VERSION' => ApiConfig::SDK_VERSION,
                'FRONTEND.CSS_PATH' => self::CSS_PATH,
                'FRONTEND.ENABLED' => 'FALSE',
                'FRONTEND.MODE' => 'WHITELABEL',
                'FRONTEND.PAYMENT_FRAME_ORIGIN' => self::PAYMENT_FRAME_ORIGIN,
                'FRONTEND.PREVENT_ASYNC_REDIRECT' => $preventAsyncRedirect,
                'IDENTIFICATION.SHOPPERID' => $shopperId,
                'IDENTIFICATION.TRANSACTIONID' => $timestamp,
                'NAME.GIVEN' => $firstName,
                'NAME.FAMILY' => $lastName,
                'PAYMENT.CODE' => self::PAYMENT_METHOD_SHORT . '.' . TransactionType::REGISTRATION,
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

    /**
     * Verify registration parameters generated as expected
     *
     * @test
     *
     * @throws \Exception
     */
    public function reregistrationParametersShouldBeSetUpAsExpected()
    {
        $timestamp = 'CreditCardPaymentMethodTest::reregistrationParametersShouldBeSetUpAsExpected 2017-10-26 15:41:12';
        $this->paymentObject->getRequest()->basketData($timestamp, self::TEST_AMOUNT, $this->currency, $this->secret);

        $preventAsyncRedirect = 'FALSE';
        $referenceId = 123456789;
        $this->paymentObject->reregistration(
            $referenceId,
            self::PAYMENT_FRAME_ORIGIN,
            $preventAsyncRedirect,
            self::CSS_PATH
        );

        /* disable frontend (iframe) and submit the card information directly (only for testing) */
        $frontendEnabled = 'FALSE';
        $this->paymentObject->getRequest()->getFrontend()->setEnabled($frontendEnabled);
        $this->paymentObject->getRequest()->getAccount()->setHolder($this->holder);
        $this->paymentObject->getRequest()->getAccount()->setNumber($this->cartNumber);
        $this->paymentObject->getRequest()->getAccount()->setExpiryMonth($this->cardExpiryMonth);
        $this->paymentObject->getRequest()->getAccount()->setExpiryYear($this->cardExpiryYear);
        $this->paymentObject->getRequest()->getAccount()->setBrand($this->cardBrand);
        $this->paymentObject->getRequest()->getAccount()->setVerification($this->cardVerification);

        list($firstName, $lastName, , $shopperId, $street, $state, $zip, $city, $country, $email) =
            $this->customerData->getCustomerDataArray();

        list($securitySender, $userLogin, $userPassword, $transactionChannel, ) =
            $this->authentication->getAuthenticationArray();

        // this is done to avoid syntax warnings
        $object = $this->paymentObject;

        $expected =
            [
                'ACCOUNT.BRAND' => $this->cardBrand,
                'ACCOUNT.EXPIRY_MONTH' => $this->cardExpiryMonth,
                'ACCOUNT.EXPIRY_YEAR' => $this->cardExpiryYear,
                'ACCOUNT.HOLDER' => $this->holder,
                'ACCOUNT.NUMBER' => $this->cartNumber,
                'ACCOUNT.VERIFICATION' => $this->cardVerification,
                'ADDRESS.CITY' => $city,
                'ADDRESS.COUNTRY' => $country,
                'ADDRESS.STATE' => $state,
                'ADDRESS.STREET' => $street,
                'ADDRESS.ZIP' => $zip,
                'CONTACT.EMAIL' => $email,
                'CRITERION.PAYMENT_METHOD' => $object::getClassName(),
                'CRITERION.SECRET' => '53ebf32eb481bc80cb4e2d36cd37599cc29328ff2f1e0bc03d2fc3e293377a43aa0c' .
                    'f3d895699fac634ee3e49024bc2eb2dedeb91929bd8150877ce328e6544e',
                'CRITERION.SDK_NAME' => 'Heidelpay\\PhpPaymentApi',
                'CRITERION.SDK_VERSION' => ApiConfig::SDK_VERSION,
                'FRONTEND.CSS_PATH' => self::CSS_PATH,
                'FRONTEND.ENABLED' => 'FALSE',
                'FRONTEND.MODE' => 'WHITELABEL',
                'FRONTEND.PAYMENT_FRAME_ORIGIN' => self::PAYMENT_FRAME_ORIGIN,
                'FRONTEND.PREVENT_ASYNC_REDIRECT' => $preventAsyncRedirect,
                'IDENTIFICATION.SHOPPERID' => $shopperId,
                'IDENTIFICATION.TRANSACTIONID' => $timestamp,
                'IDENTIFICATION.REFERENCEID' => $referenceId,
                'NAME.GIVEN' => $firstName,
                'NAME.FAMILY' => $lastName,
                'PAYMENT.CODE' => self::PAYMENT_METHOD_SHORT . '.' . TransactionType::REREGISTRATION,
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

    /**
     * Verify authorize parameters generated as expected
     *
     * @test
     *
     * @throws \Exception
     */
    public function authorizeParametersShouldBeSetUpAsExpected()
    {
        $timestamp = 'CreditCardPaymentMethodTest::authorize 2017-10-27 13:10:40';
        $this->paymentObject->getRequest()->basketData($timestamp, self::TEST_AMOUNT, $this->currency, $this->secret);

        $preventAsyncRedirect = 'FALSE';
        $this->paymentObject->authorize(self::PAYMENT_FRAME_ORIGIN, $preventAsyncRedirect, self::CSS_PATH);

        /* disable frontend (ifame) and submit the card information directly (only for testing) */
        $frontendEnabled = 'FALSE';
        $this->paymentObject->getRequest()->getFrontend()->setEnabled($frontendEnabled);
        $this->paymentObject->getRequest()->getAccount()->setHolder($this->holder);
        $this->paymentObject->getRequest()->getAccount()->setNumber($this->cartNumber);
        $this->paymentObject->getRequest()->getAccount()->setExpiryMonth($this->cardExpiryMonth);
        $this->paymentObject->getRequest()->getAccount()->setExpiryYear($this->cardExpiryYear);
        $this->paymentObject->getRequest()->getAccount()->setBrand($this->cardBrand);
        $this->paymentObject->getRequest()->getAccount()->setVerification($this->cardVerification);

        list($firstName, $lastName, , $shopperId, $street, $state, $zip, $city, $country, $email) =
            $this->customerData->getCustomerDataArray();

        list($securitySender, $userLogin, $userPassword, $transactionChannel, ) =
            $this->authentication->getAuthenticationArray();

        // this is done to avoid syntax warnings
        $object = $this->paymentObject;

        $expected = [
            'ACCOUNT.BRAND' => $this->cardBrand,
            'ACCOUNT.EXPIRY_MONTH' => $this->cardExpiryMonth,
            'ACCOUNT.EXPIRY_YEAR' => $this->cardExpiryYear,
            'ACCOUNT.HOLDER' => $this->holder,
            'ACCOUNT.NUMBER' => $this->cartNumber,
            'ACCOUNT.VERIFICATION' => $this->cardVerification,
            'ADDRESS.CITY' => $city,
            'ADDRESS.COUNTRY' => $country,
            'ADDRESS.STATE' => $state,
            'ADDRESS.STREET' => $street,
            'ADDRESS.ZIP' => $zip,
            'CONTACT.EMAIL' => $email,
            'CRITERION.PAYMENT_METHOD' => $object::getClassName(),
            'CRITERION.SECRET' => '965e4267bc0d3e9313f07aaff8ac602681d9e1643677dc4c39853022c227ff23ef30ca' .
                '1f92b955c2f2335becba777fe2c3ad4b036c62deb81d1b2800fb31e655',
            'CRITERION.SDK_NAME' => 'Heidelpay\\PhpPaymentApi',
            'CRITERION.SDK_VERSION' => ApiConfig::SDK_VERSION,
            'FRONTEND.CSS_PATH' => self::CSS_PATH,
            'FRONTEND.ENABLED' => $frontendEnabled,
            'FRONTEND.MODE' => 'WHITELABEL',
            'FRONTEND.PAYMENT_FRAME_ORIGIN' => self::PAYMENT_FRAME_ORIGIN,
            'FRONTEND.PREVENT_ASYNC_REDIRECT' => $preventAsyncRedirect,
            'IDENTIFICATION.SHOPPERID' => $shopperId,
            'IDENTIFICATION.TRANSACTIONID' => $timestamp,
            'NAME.GIVEN' => $firstName,
            'NAME.FAMILY' => $lastName,
            'PAYMENT.CODE' => self::PAYMENT_METHOD_SHORT . '.' . TransactionType::RESERVATION,
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

    /**
     * Verify that authorize does not overwrite parameters when it is called without any arguments
     *
     * @test
     *
     * @throws \Exception
     */
    public function authorizeShouldNotOverwriteParametersWhenCalledWithNoArguments()
    {
        $timestamp = 'CreditCardPaymentMethodTest::authorize 2017-10-27 13:37:00';
        $this->paymentObject->getRequest()->basketData($timestamp, self::TEST_AMOUNT, $this->currency, $this->secret);

        $preventAsyncRedirect = 'FALSE';
        $this->paymentObject->getRequest()->getFrontend()->setPaymentFrameOrigin(self::PAYMENT_FRAME_ORIGIN);
        $this->paymentObject->getRequest()->getFrontend()->setPreventAsyncRedirect($preventAsyncRedirect);
        $this->paymentObject->getRequest()->getFrontend()->setCssPath(self::CSS_PATH);

        $this->paymentObject->authorize();

        $this->assertEquals($this->paymentObject->getRequest()->getFrontend()->getPaymentFrameOrigin(), self::PAYMENT_FRAME_ORIGIN);
        $this->assertEquals($this->paymentObject->getRequest()->getFrontend()->getPreventAsyncRedirect(), $preventAsyncRedirect);
        $this->assertEquals($this->paymentObject->getRequest()->getFrontend()->getCssPath(), self::CSS_PATH);
    }

    /**
     * Verify debit parameters generated as expected
     *
     * @test
     *
     * @throws \Exception
     */
    public function debitParametersShouldBeSetUpAsExpected()
    {
        $timestamp = 'CreditCardPaymentMethodTest::debit 2017-10-27 13:24:08';
        $this->paymentObject->getRequest()->basketData($timestamp, self::TEST_AMOUNT, $this->currency, $this->secret);

        $preventAsyncRedirect = 'FALSE';
        $this->paymentObject->debit(self::PAYMENT_FRAME_ORIGIN, $preventAsyncRedirect, self::CSS_PATH);

        /* disable frontend (ifame) and submit the card information directly (only for testing) */
        $frontendEnabled = 'FALSE';
        $this->paymentObject->getRequest()->getFrontend()->setEnabled($frontendEnabled);
        $this->paymentObject->getRequest()->getAccount()->setHolder($this->holder);
        $this->paymentObject->getRequest()->getAccount()->setNumber($this->cartNumber);
        $this->paymentObject->getRequest()->getAccount()->setExpiryMonth($this->cardExpiryMonth);
        $this->paymentObject->getRequest()->getAccount()->setExpiryYear($this->cardExpiryYear);
        $this->paymentObject->getRequest()->getAccount()->setBrand($this->cardBrand);
        $this->paymentObject->getRequest()->getAccount()->setVerification($this->cardVerification);

        list($firstName, $lastName, , $shopperId, $street, $state, $zip, $city, $country, $email) =
            $this->customerData->getCustomerDataArray();

        list($securitySender, $userLogin, $userPassword, $transactionChannel, ) =
            $this->authentication->getAuthenticationArray();

        // this is done to avoid syntax warnings
        $object = $this->paymentObject;

        $expected = [
            'ACCOUNT.BRAND' => $this->cardBrand,
            'ACCOUNT.EXPIRY_MONTH' => $this->cardExpiryMonth,
            'ACCOUNT.EXPIRY_YEAR' => $this->cardExpiryYear,
            'ACCOUNT.HOLDER' => $this->holder,
            'ACCOUNT.NUMBER' => $this->cartNumber,
            'ACCOUNT.VERIFICATION' => $this->cardVerification,
            'ADDRESS.CITY' => $city,
            'ADDRESS.COUNTRY' => $country,
            'ADDRESS.STATE' => $state,
            'ADDRESS.STREET' => $street,
            'ADDRESS.ZIP' => $zip,
            'CONTACT.EMAIL' => $email,
            'CRITERION.PAYMENT_METHOD' => $object::getClassName(),
            'CRITERION.SECRET' => '3fcdb7e573521e40823f5875195f635f4cbdda39c6de0e8a10b816313ace6b4f1d77' .
                'cfa6b38ea9a575e7b038530caa49966df3794014d8ded8bf5a5f79185e56',
            'CRITERION.SDK_NAME' => 'Heidelpay\\PhpPaymentApi',
            'CRITERION.SDK_VERSION' => ApiConfig::SDK_VERSION,
            'FRONTEND.CSS_PATH' => self::CSS_PATH,
            'FRONTEND.ENABLED' => $frontendEnabled,
            'FRONTEND.MODE' => 'WHITELABEL',
            'FRONTEND.PAYMENT_FRAME_ORIGIN' => self::PAYMENT_FRAME_ORIGIN,
            'FRONTEND.PREVENT_ASYNC_REDIRECT' => $preventAsyncRedirect,
            'IDENTIFICATION.SHOPPERID' => $shopperId,
            'IDENTIFICATION.TRANSACTIONID' => $timestamp,
            'NAME.GIVEN' => $firstName,
            'NAME.FAMILY' => $lastName,
            'PAYMENT.CODE' => self::PAYMENT_METHOD_SHORT . '.' . TransactionType::DEBIT,
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

    /**
     * Verify that debit does not overwrite parameters when it is called without any arguments
     *
     * @test
     *
     * @throws \Exception
     */
    public function debitShouldNotOverwriteParametersWhenCalledWithNoArguments()
    {
        $timestamp = 'CreditCardPaymentMethodTest::debit 2017-10-27 13:37:00';
        $this->paymentObject->getRequest()->basketData($timestamp, self::TEST_AMOUNT, $this->currency, $this->secret);

        $preventAsyncRedirect = 'FALSE';
        $this->paymentObject->getRequest()->getFrontend()->setPaymentFrameOrigin(self::PAYMENT_FRAME_ORIGIN);
        $this->paymentObject->getRequest()->getFrontend()->setPreventAsyncRedirect($preventAsyncRedirect);
        $this->paymentObject->getRequest()->getFrontend()->setCssPath(self::CSS_PATH);

        $this->paymentObject->debit();

        $this->assertEquals($this->paymentObject->getRequest()->getFrontend()->getPaymentFrameOrigin(), self::PAYMENT_FRAME_ORIGIN);
        $this->assertEquals($this->paymentObject->getRequest()->getFrontend()->getPreventAsyncRedirect(), $preventAsyncRedirect);
        $this->assertEquals($this->paymentObject->getRequest()->getFrontend()->getCssPath(), self::CSS_PATH);
    }

    /**
     * Verify that registration does not overwrite parameters when it is called without any arguments
     *
     * @test
     *
     * @throws \Exception
     */
    public function registrationShouldNotOverwriteParametersWhenCalledWithNoArguments()
    {
        $timestamp = 'CreditCardPaymentMethodTest::registration 2017-10-27 13:37:00';
        $this->paymentObject->getRequest()->basketData($timestamp, self::TEST_AMOUNT, $this->currency, $this->secret);

        $preventAsyncRedirect = 'FALSE';
        $this->paymentObject->getRequest()->getFrontend()->setPaymentFrameOrigin(self::PAYMENT_FRAME_ORIGIN);
        $this->paymentObject->getRequest()->getFrontend()->setPreventAsyncRedirect($preventAsyncRedirect);
        $this->paymentObject->getRequest()->getFrontend()->setCssPath(self::CSS_PATH);

        $this->paymentObject->registration();

        $this->assertEquals($this->paymentObject->getRequest()->getFrontend()->getPaymentFrameOrigin(), self::PAYMENT_FRAME_ORIGIN);
        $this->assertEquals($this->paymentObject->getRequest()->getFrontend()->getPreventAsyncRedirect(), $preventAsyncRedirect);
        $this->assertEquals($this->paymentObject->getRequest()->getFrontend()->getCssPath(), self::CSS_PATH);
    }

    /**
     * Verify that reregistration does not overwrite parameters when it is called without any arguments
     *
     * @test
     *
     * @throws \Exception
     */
    public function reregistrationShouldNotOverwriteParametersWhenCalledWithNoArguments()
    {
        $timestamp = 'CreditCardPaymentMethodTest::registration 2017-10-27 13:37:00';
        $this->paymentObject->getRequest()->basketData($timestamp, self::TEST_AMOUNT, $this->currency, $this->secret);

        $preventAsyncRedirect = 'FALSE';
        $this->paymentObject->getRequest()->getFrontend()->setPaymentFrameOrigin(self::PAYMENT_FRAME_ORIGIN);
        $this->paymentObject->getRequest()->getFrontend()->setPreventAsyncRedirect($preventAsyncRedirect);
        $this->paymentObject->getRequest()->getFrontend()->setCssPath(self::CSS_PATH);

        $referenceId = '123';
        $this->paymentObject->reregistration($referenceId);

        $this->assertEquals($this->paymentObject->getRequest()->getIdentification()->getReferenceId(), $referenceId);
        $this->assertEquals($this->paymentObject->getRequest()->getFrontend()->getPaymentFrameOrigin(), self::PAYMENT_FRAME_ORIGIN);
        $this->assertEquals($this->paymentObject->getRequest()->getFrontend()->getPreventAsyncRedirect(), $preventAsyncRedirect);
        $this->assertEquals($this->paymentObject->getRequest()->getFrontend()->getCssPath(), self::CSS_PATH);
    }

    //</editor-fold>
}
