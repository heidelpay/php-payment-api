<?php

namespace Heidelpay\Tests\PhpApi\Unit\PaymentMethods;

use AspectMock\Proxy\InstanceProxy;
use AspectMock\Test as test;
use Heidelpay\PhpApi\PaymentMethods\IdealPaymentMethod;
use Heidelpay\Tests\PhpApi\Helper\BasePaymentMethodTest;

/**
 * Ideal Test
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
class IdealPaymentMethodTest extends BasePaymentMethodTest
{
    const REFERENCE_ID = 'http://www.heidelpay.de';
    const PAYMENT_FRAME_ORIGIN = self::REFERENCE_ID;
    const REDIRECT_URL = 'https://dev.heidelpay.de';
    const CSS_PATH = self::REFERENCE_ID;
    const TEST_AMOUNT = 23.12;
    const PAYMENT_METHOD = 'IDealPaymentMethod';
    const PAYMENT_METHOD_SHORT = 'OT';
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
     * @var IdealPaymentMethod $paymentObject
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

        $paymentMethod = new IdealPaymentMethod();
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
            ['refund', null,'RF']
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
        $timestamp = 'IdealPaymentMethodTest::authorizeParametersShouldBeSetUpAsExpected 2017-11-22 11:29:32';
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

        $expected = [
            'ACCOUNT.BRAND' => 'IDEAL',
            'ACCOUNT.IBAN' => $this->iban,
            'ACCOUNT.HOLDER' => $this->holder,
            'ADDRESS.CITY' => $city,
            'ADDRESS.COUNTRY' => $country,
            'ADDRESS.STATE' => $state,
            'ADDRESS.STREET' => $street,
            'ADDRESS.ZIP' => $zip,
            'CONTACT.EMAIL' => $email,
            'CRITERION.PAYMENT_METHOD' => self::PAYMENT_METHOD,
            'CRITERION.SECRET' => '9c637cee9529b8b5ac6d7d43da5860355cca894df3505189ef348dbf93ec723a26'.
                '9fbfed4ad2a7c468188d6362ead1132891250ac9e2f221d60e3e758d4de926',
            'CRITERION.SDK_NAME' => 'Heidelpay\PhpApi',
            'CRITERION.SDK_VERSION' => '17.9.27',
            'FRONTEND.ENABLED' => $frontendEnabled,
            'FRONTEND.MODE' => 'WHITELABEL',
            'FRONTEND.RESPONSE_URL' => self::REDIRECT_URL,
            'FRONTEND.LANGUAGE' => 'DE',
            'IDENTIFICATION.SHOPPERID' => $shopperId,
            'IDENTIFICATION.TRANSACTIONID' => $timestamp,
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
        $timestamp = 'IdealPaymentMethodTest::refundParametersShouldBeSetUpAsExpected 2017-11-22 11:32:57';
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
            'ACCOUNT.BRAND' => 'IDEAL',
            'ADDRESS.CITY' => $city,
            'ADDRESS.COUNTRY' => $country,
            'ADDRESS.STATE' => $state,
            'ADDRESS.STREET' => $street,
            'ADDRESS.ZIP' => $zip,
            'CONTACT.EMAIL' => $email,
            'CRITERION.PAYMENT_METHOD' => self::PAYMENT_METHOD,
            'CRITERION.SECRET' => 'c0a7f74953327c916d139bdec51f6978230dba95b74d115323a21e819549aad924050e69'.
                'c5aa35b96aec27d2374a0f3589c5f4c2f73a381464d6c8bc217c6039',
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

    //</editor-fold>
}
