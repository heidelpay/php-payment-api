<?php

namespace Heidelpay\Tests\PhpPaymentApi\Unit\PaymentMethods;

use AspectMock\Test as test;
use Heidelpay\PhpPaymentApi\ParameterGroups\CriterionParameterGroup;
use Heidelpay\PhpPaymentApi\PaymentMethods\DebitCardPaymentMethod;
use Heidelpay\Tests\PhpPaymentApi\Helper\BasePaymentMethodTest;

/**
 * This test class verifies the special functionality of the DebitCardPaymentMethod not covered in
 * GenericPaymentMethodTest and PaymentMethodTransactionTest.
 * There is no actual communication to the server since the curl adapter is being mocked.
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author  Simon Gabriel
 *
 * @package  Heidelpay
 * @subpackage PhpPaymentApi
 * @category UnitTest
 */
class DebitCardPaymentMethodTest extends BasePaymentMethodTest
{
    const PAYMENT_METHOD_SHORT = 'DC';

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
        $paymentObject->_dryRun = false;

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

        $paymentFrameOrigin = 'http://www.heidelpay.de';
        $cssPath = 'http://www.heidelpay.de';
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
        $this->paymentObject->getRequest()->getAccount()->set('expiry_month', $this->cardExpiryMonth);
        $this->paymentObject->getRequest()->getAccount()->set('expiry_year', $this->cardExpiryYear);
        $this->paymentObject->getRequest()->getAccount()->setBrand($this->cardBrand);
        $this->paymentObject->getRequest()->getAccount()->set('verification', $this->cardVerification);

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
                'CRITERION.SDK_VERSION' => CriterionParameterGroup::SDK_VERSION,
                'FRONTEND.CSS_PATH' => $cssPath,
                'FRONTEND.ENABLED' => 'FALSE',
                'FRONTEND.MODE' => 'WHITELABEL',
                'FRONTEND.PAYMENT_FRAME_ORIGIN' => $paymentFrameOrigin,
                'FRONTEND.PREVENT_ASYNC_REDIRECT' => $preventAsyncRedirect,
                'IDENTIFICATION.SHOPPERID' => $shopperId,
                'IDENTIFICATION.TRANSACTIONID' => $timestamp,
                'NAME.GIVEN' => $firstName,
                'NAME.FAMILY' => $lastName,
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

        $this->assertThat($this->paymentObject->getRequest()->convertToArray(), $this->arraysMatchExactly($expected));
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
        $timestamp = 'DebitCardPaymentMethodTest::authorize 2017-10-27 13:10:40';
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
        $this->paymentObject->getRequest()->getAccount()->set('expiry_month', $this->cardExpiryMonth);
        $this->paymentObject->getRequest()->getAccount()->set('expiry_year', $this->cardExpiryYear);
        $this->paymentObject->getRequest()->getAccount()->setBrand($this->cardBrand);
        $this->paymentObject->getRequest()->getAccount()->set('verification', $this->cardVerification);

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
            'CRITERION.SECRET' => '6f9d4135538597a082ed393edc4fc6bdc535118b1f01be319e3ae3eeaf09d17dcefc1' .
                '7a9c01bb1031ceaf68eea615ac971cc9de06f166335e40795d874dde889',
            'CRITERION.SDK_NAME' => 'Heidelpay\\PhpPaymentApi',
            'CRITERION.SDK_VERSION' => CriterionParameterGroup::SDK_VERSION,
            'FRONTEND.CSS_PATH' => self::CSS_PATH,
            'FRONTEND.ENABLED' => $frontendEnabled,
            'FRONTEND.MODE' => 'WHITELABEL',
            'FRONTEND.PAYMENT_FRAME_ORIGIN' => self::PAYMENT_FRAME_ORIGIN,
            'FRONTEND.PREVENT_ASYNC_REDIRECT' => $preventAsyncRedirect,
            'IDENTIFICATION.SHOPPERID' => $shopperId,
            'IDENTIFICATION.TRANSACTIONID' => $timestamp,
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

        $this->assertThat($this->paymentObject->getRequest()->convertToArray(), $this->arraysMatchExactly($expected));
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
        $timestamp = 'DebitCardPaymentMethodTest::debit 2017-10-27 13:24:08';
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
        $this->paymentObject->getRequest()->getAccount()->set('expiry_month', $this->cardExpiryMonth);
        $this->paymentObject->getRequest()->getAccount()->set('expiry_year', $this->cardExpiryYear);
        $this->paymentObject->getRequest()->getAccount()->setBrand($this->cardBrand);
        $this->paymentObject->getRequest()->getAccount()->set('verification', $this->cardVerification);

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
            'CRITERION.SECRET' => '6867a929d7c41dcd2b425eaf10f1e8229cf630d3a6a5658d4ce72ce0f8f9e6' .
                'ae3e13ba64ec41e448724e2a13f683bb5eb29a31517db61dc46426ff0694bc7d15',
            'CRITERION.SDK_NAME' => 'Heidelpay\\PhpPaymentApi',
            'CRITERION.SDK_VERSION' => CriterionParameterGroup::SDK_VERSION,
            'FRONTEND.CSS_PATH' => self::CSS_PATH,
            'FRONTEND.ENABLED' => $frontendEnabled,
            'FRONTEND.MODE' => 'WHITELABEL',
            'FRONTEND.PAYMENT_FRAME_ORIGIN' => self::PAYMENT_FRAME_ORIGIN,
            'FRONTEND.PREVENT_ASYNC_REDIRECT' => $preventAsyncRedirect,
            'IDENTIFICATION.SHOPPERID' => $shopperId,
            'IDENTIFICATION.TRANSACTIONID' => $timestamp,
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

        $this->assertThat($this->paymentObject->getRequest()->convertToArray(), $this->arraysMatchExactly($expected));
    }

    //</editor-fold>
}
