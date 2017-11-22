<?php

namespace Heidelpay\Tests\PhpApi\Unit\PaymentMethods;

use AspectMock\Proxy\InstanceProxy;
use AspectMock\Test as test;
use Heidelpay\PhpApi\PaymentMethods\EasyCreditPaymentMethod;
use Heidelpay\Tests\PhpApi\Helper\BasePaymentMethodTest;

/**
 * EasyCredit Test
 *
 * Connection tests can fail due to network issues and scheduled downtime.
 * This does not have to mean that your integration is broken. Please verify the given debug information
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
class EasyCreditPaymentMethodTest extends BasePaymentMethodTest
{
    const REFERENCE_ID = 'http://www.heidelpay.de';
    const PAYMENT_FRAME_ORIGIN = self::REFERENCE_ID;
    const REDIRECT_URL = 'https://dev.heidelpay.de';
    const CSS_PATH = self::REFERENCE_ID;
    const TEST_AMOUNT = 23.12;
    const PAYMENT_METHOD = 'EasyCreditPaymentMethod';
    const PAYMENT_METHOD_SHORT = 'HP';
    const NAME_COMPANY = 'DevHeidelpay';

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
     * Customers IBAN
     *
     * @var string $iban
     */
    protected $iban = 'DE89370400440532013000';

    /**
     * PaymentObject
     *
     * @var EasyCreditPaymentMethod $paymentObject
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
        $authentication = $this->authentication
            ->setTransactionChannel('31HA07BC8142C5A171749A60D979B6E4')
            ->getAuthenticationArray();
        $customerDetails = $this->customerData
            ->setCompanyName(self::NAME_COMPANY)
            ->getCustomerDataArray();

        $paymentMethod = new EasyCreditPaymentMethod();
        $paymentMethod->getRequest()->authentification(...$authentication);
        $paymentMethod->getRequest()->customerAddress(...$customerDetails);
        $paymentMethod->_dryRun = false;

        $this->paymentObject = $paymentMethod;

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
    public function initializeRequestShouldSetCriterionPaymentMethod()
    {
        $criterionParameterGroup = $this->paymentObject->getRequest()->getCriterion();

        // verify initial state
        $this->assertNull($criterionParameterGroup->getPaymentMethod());

        $this->paymentObject->initialize();

        // verify the criterion values changed as expected
        $this->assertEquals(self::PAYMENT_METHOD, $criterionParameterGroup->getPaymentMethod());
    }

    //</editor-fold>

    /**
     * Verify authorizeOnInitialization parameters generated as expected
     *
     * @test
     */
    public function authorizeOnInitializationParametersShouldBeSetUpAsExpected()
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

        $expected = [
            'ADDRESS.CITY' => $city,
            'ADDRESS.COUNTRY' => $country,
            'ADDRESS.STATE' => $state,
            'ADDRESS.STREET' => $street,
            'ADDRESS.ZIP' => $zip,
            'CONTACT.EMAIL' => $email,
            'CRITERION.PAYMENT_METHOD' => self::PAYMENT_METHOD,
            'CRITERION.SECRET' => 'aaef3d8af1f5b6d4e8f11f7d6575dd780ebe2631de3f604c702a8b3aeb2fc555511473809ce'.
                '118fb5e55eece5777e5e19435af91b85ae753ecdaf5f4b3dbbc32',
            'CRITERION.SDK_NAME' => 'Heidelpay\\PhpApi',
            'CRITERION.SDK_VERSION' => '17.9.27',
            'FRONTEND.ENABLED' => $frontendEnabled,
            'FRONTEND.MODE' => 'WHITELABEL',
            'IDENTIFICATION.SHOPPERID' => $shopperId,
            'IDENTIFICATION.TRANSACTIONID' => $timestamp,
            'IDENTIFICATION.REFERENCEID' => self::REFERENCE_ID,
            'NAME.COMPANY' => self::NAME_COMPANY,
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

        $expected = [
            'ADDRESS.CITY' => $city,
            'ADDRESS.COUNTRY' => $country,
            'ADDRESS.STATE' => $state,
            'ADDRESS.STREET' => $street,
            'ADDRESS.ZIP' => $zip,
            'CONTACT.EMAIL' => $email,
            'CRITERION.PAYMENT_METHOD' => self::PAYMENT_METHOD,
            'CRITERION.SECRET' => '429c40329f9534646f94d5eda42f98453575c214d06ef620557a5ce0edad5d22a60e902d92c9'.
                '0327b19d2d28a8607ff494bbd6717a9919495e008bfa732c3ddd',
            'CRITERION.SDK_NAME' => 'Heidelpay\\PhpApi',
            'CRITERION.SDK_VERSION' => '17.9.27',
            'FRONTEND.MODE' => 'WHITELABEL',
            'FRONTEND.ENABLED' => 'FALSE',
            'IDENTIFICATION.SHOPPERID' => $shopperId,
            'IDENTIFICATION.TRANSACTIONID' => $timestamp,
            'IDENTIFICATION.REFERENCEID' => self::REFERENCE_ID,
            'NAME.COMPANY' => self::NAME_COMPANY,
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

        $expected = [
            'ADDRESS.CITY' => $city,
            'ADDRESS.COUNTRY' => $country,
            'ADDRESS.STATE' => $state,
            'ADDRESS.STREET' => $street,
            'ADDRESS.ZIP' => $zip,
            'CONTACT.EMAIL' => $email,
            'CRITERION.PAYMENT_METHOD' => self::PAYMENT_METHOD,
            'CRITERION.SECRET' => '64c1c52f4fbf93a850ef1f879ce75dee9d389e024f0abda12d95a33903eb8461f17'.
                '9b659c27396173aa3a8ee9abbc876bccdab1458a7d0d818bac1c692177ed0',
            'CRITERION.SDK_NAME' => 'Heidelpay\\PhpApi',
            'CRITERION.SDK_VERSION' => '17.9.27',
            'FRONTEND.ENABLED' => 'FALSE',
            'FRONTEND.MODE' => 'WHITELABEL',
            'IDENTIFICATION.SHOPPERID' => $shopperId,
            'IDENTIFICATION.TRANSACTIONID' => $timestamp,
            'IDENTIFICATION.REFERENCEID' => self::REFERENCE_ID,
            'NAME.COMPANY' => self::NAME_COMPANY,
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

    /**
     * Verify finalize parameters generated as expected
     *
     * @test
     */
    public function finalizeParametersShouldBeSetUpAsExpected()
    {
        $timestamp = 'EasyCreditPaymentMethodTest::finalizeParametersShouldBeSetUpAsExpected 2017-11-22 09:48:02';
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

        $expected = [
            'ACCOUNT.BRAND' => 'EASYCREDIT',
            'ADDRESS.CITY' => $city,
            'ADDRESS.COUNTRY' => $country,
            'ADDRESS.STATE' => $state,
            'ADDRESS.STREET' => $street,
            'ADDRESS.ZIP' => $zip,
            'CONTACT.EMAIL' => $email,
            'CRITERION.PAYMENT_METHOD' => self::PAYMENT_METHOD,
            'CRITERION.SECRET' => '34319213e5de83737a352486a9289a1d0d97b3e905fa06a92190c91288169d60'.
                'a1c9b493216998825760f99b1f8b4512fe1413e8cbf44d4a9f582196a304e228',
            'CRITERION.SDK_NAME' => 'Heidelpay\\PhpApi',
            'CRITERION.SDK_VERSION' => '17.9.27',
            'FRONTEND.ENABLED' => 'FALSE',
            'FRONTEND.MODE' => 'WHITELABEL',
            'IDENTIFICATION.SHOPPERID' => $shopperId,
            'IDENTIFICATION.TRANSACTIONID' => $timestamp,
            'IDENTIFICATION.REFERENCEID' => self::REFERENCE_ID,
            'NAME.GIVEN' => $firstName,
            'NAME.FAMILY' => $lastName,
            'NAME.COMPANY' => self::NAME_COMPANY,
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

        $this->assertThat($this->paymentObject->getRequest()->convertToArray(), $this->arraysMatchExactly($expected));
    }

    /**
     * Verify initialize parameters generated as expected
     *
     * @test
     */
    public function initializeParametersShouldBeSetUpAsExpected()
    {
        $timestamp = 'EasyCreditPaymentMethodTest::initializeParametersShouldBeSetUpAsExpected 2017-11-22 09:59:53';
        $this->paymentObject->getRequest()->basketData(
            $timestamp,
            self::TEST_AMOUNT,
            $this->currency,
            $this->secret
        );

        $this->paymentObject->initialize();

        list($firstName, $lastName, , $shopperId, $street, $state, $zip, $city, $country, $email) =
            $this->customerData->getCustomerDataArray();

        list($securitySender, $userLogin, $userPassword, $transactionChannel, ) =
            $this->authentication->getAuthenticationArray();

        $expected = [
            'ACCOUNT.BRAND' => 'EASYCREDIT',
            'ADDRESS.CITY' => $city,
            'ADDRESS.COUNTRY' => $country,
            'ADDRESS.STATE' => $state,
            'ADDRESS.STREET' => $street,
            'ADDRESS.ZIP' => $zip,
            'CONTACT.EMAIL' => $email,
            'CRITERION.PAYMENT_METHOD' => self::PAYMENT_METHOD,
            'CRITERION.SECRET' => '167c413bc66cbc5c9978dfafc5aaa20dc6c78d12fa5b83ea38014d9a08011b6e32a1e4f4'.
                '2bc4b8ecadd511d7a737e932798884d230def3d4ebba9dd9517f47b6',
            'CRITERION.SDK_NAME' => 'Heidelpay\\PhpApi',
            'CRITERION.SDK_VERSION' => '17.9.27',
            'FRONTEND.ENABLED' => 'TRUE',
            'FRONTEND.MODE' => 'WHITELABEL',
            'IDENTIFICATION.SHOPPERID' => $shopperId,
            'IDENTIFICATION.TRANSACTIONID' => $timestamp,
            'NAME.GIVEN' => $firstName,
            'NAME.FAMILY' => $lastName,
            'NAME.COMPANY' => self::NAME_COMPANY,
            'PAYMENT.CODE' => self::PAYMENT_METHOD_SHORT . '.IN',
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
