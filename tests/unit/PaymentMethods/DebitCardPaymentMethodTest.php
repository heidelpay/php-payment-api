<?php

namespace Heidelpay\Tests\PhpPaymentApi\Unit\PaymentMethods;

use AspectMock\Test as test;
use Heidelpay\PhpPaymentApi\Constants\ApiConfig;
use Heidelpay\PhpPaymentApi\Constants\PaymentMethod;
use Heidelpay\PhpPaymentApi\Constants\TransactionMode;
use Heidelpay\PhpPaymentApi\Constants\TransactionType;
use Heidelpay\PhpPaymentApi\PaymentMethods\DebitCardPaymentMethod;
use Heidelpay\Tests\PhpPaymentApi\Helper\BasePaymentMethodTest;

/**
 * This test class verifies the special functionality of the DebitCardPaymentMethod not covered in
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
class DebitCardPaymentMethodTest extends BasePaymentMethodTest
{
    const PAYMENT_METHOD_SHORT = PaymentMethod::DEBIT_CARD;

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
    protected $cardBrand = 'VISAELECTRON';
    /**
     * Card verification
     * Do not use real card information for this test. For more details read the information
     * on top of this test class.
     *
     * @var string $cardVerification
     */
    protected $cardVerification = '123';
    /**
     * Credit card expiry month
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
     * @var DebitCardPaymentMethod $paymentObject
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
        $authentication = $this->authentication->setTransactionChannel('31HA07BC8142C5A171744F3D6D155865');
        $customerDetails = $this->customerData;

        $paymentObject = new DebitCardPaymentMethod();
        $paymentObject->getRequest()->authentification(...$authentication->getAuthenticationArray());
        $paymentObject->getRequest()->customerAddress(...$customerDetails->getCustomerDataArray());
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
        $timestamp = 'DebitCardPaymentMethodTest::registrationParametersShouldBeSetUpAsExpected 2017-10-26 15:41:12';
        $this->paymentObject->getRequest()->basketData(
            $timestamp,
            self::TEST_AMOUNT,
            $this->currency,
            $this->secret
        );

        $paymentFrameOrigin = 'http://www.heidelpay.com';
        $cssPath = 'http://www.heidelpay.com';
        $preventAsyncRedirect = 'FALSE';
        $this->paymentObject->registration(
            $paymentFrameOrigin,
            $preventAsyncRedirect,
            $cssPath
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
                'CRITERION.SECRET' => '39fca69e5c569134ba2b34b43916692e7dfb2200adb9c85da67bb0fa4bb49faaa7' .
                    'a151930c2a08de1ad6f8a3d11edb00ab071889ac2505c02a898a8e3ba68987',
                'CRITERION.SDK_NAME' => 'Heidelpay\\PhpPaymentApi',
                'CRITERION.SDK_VERSION' => ApiConfig::SDK_VERSION,
                'FRONTEND.CSS_PATH' => $cssPath,
                'FRONTEND.ENABLED' => 'FALSE',
                'FRONTEND.MODE' => 'WHITELABEL',
                'FRONTEND.PAYMENT_FRAME_ORIGIN' => $paymentFrameOrigin,
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
        $timestamp = 'DebitCardPaymentMethodTest::reregistrationParametersShouldBeSetUpAsExpected 2017-10-26 15:41:12';
        $this->paymentObject->getRequest()->basketData($timestamp, self::TEST_AMOUNT, $this->currency, $this->secret);

        $preventAsyncRedirect = 'FALSE';
        $referenceId = 987654321;
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
                'CRITERION.SECRET' => 'd0b4585746164f19fbbea1c55ea88a1776f79b72b0ca4aee770413dbd1c79123f8d15aed7' .
                    '5e4642b03b4dc604c6c41d1bc8b5f55212022f012ec4c3d57c6172d',
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
        $timestamp = 'DebitCardPaymentMethodTest::authorizeParametersShouldBeSetUpAsExpected 2017-10-27 13:10:40';
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
            'CRITERION.SECRET' => '5350067cbc3a27a5772bd5a193788a832ff5bbab9dfe44f18539ec512a700d04748bb43b546f1' .
                'fe8c3b8f1b99a28a376c2119d04fe51ef06b4f8456c69ce6d92',
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
        $timestamp = 'DebitCardPaymentMethodTest::authorize 2017-10-27 13:37:00';
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
        $timestamp = 'DebitCardPaymentMethodTest::debitParametersShouldBeSetUpAsExpected 2017-10-27 13:24:08';
        $this->paymentObject->getRequest()->basketData($timestamp, self::TEST_AMOUNT, $this->currency, $this->secret);

        $preventAsyncRedirect = 'FALSE';
        $this->paymentObject->debit(
            self::PAYMENT_FRAME_ORIGIN,
            $preventAsyncRedirect,
            self::CSS_PATH
        );

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
            'CRITERION.SECRET' => '58c2799a16d7deedc91193ae728898ff9051d9fe618b888e770f34c240771edfadf999583f60' .
                '725dba2f00292c2a4b6d8b358112154032d97ded97757a0425b1',
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
        $timestamp = 'DebitCardPaymentMethodTest::debit 2017-10-27 13:37:00';
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
        $timestamp = 'DebitCardPaymentMethodTest::registration 2017-10-27 13:37:00';
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
        $timestamp = 'DebitCardPaymentMethodTest::reregistration 2017-10-27 13:37:00';
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
