<?php

namespace Heidelpay\Tests\PhpPaymentApi\Unit\PaymentMethods;

use AspectMock\Test as test;
use Heidelpay\PhpPaymentApi\Constants\ApiConfig;
use Heidelpay\PhpPaymentApi\Constants\PaymentMethod;
use Heidelpay\PhpPaymentApi\Constants\TransactionType;
use Heidelpay\PhpPaymentApi\PaymentMethods\EasyCreditPaymentMethod;
use Heidelpay\Tests\PhpPaymentApi\Helper\BasePaymentMethodTest;
use Heidelpay\PhpPaymentApi\Constants\TransactionMode;

/**
 * This test class verifies the special functionality of the EasyCreditPaymentMethod not covered in
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
class EasyCreditPaymentMethodTest extends BasePaymentMethodTest
{
    const PAYMENT_METHOD_SHORT = PaymentMethod::HIRE_PURCHASE;

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

        $paymentObject = new EasyCreditPaymentMethod();
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
     * Verify initialize parameters generated as expected
     *
     * @test
     */
    public function initializeParametersShouldBeSetUpAsExpected()
    {
        $timestamp = 'EasyCreditPaymentMethodTest::initializeParametersShouldBeSetUpAsExpected 2017-11-23 11:41:54';
        $this->paymentObject->getRequest()->basketData($timestamp, self::TEST_AMOUNT, $this->currency, $this->secret);

        $this->paymentObject->initialize();

        list($firstName, $lastName, , $shopperId, $street, $state, $zip, $city, $country, $email) =
            $this->customerData->getCustomerDataArray();

        list($securitySender, $userLogin, $userPassword, $transactionChannel, ) =
            $this->authentication->getAuthenticationArray();

        // this is done to avoid syntax warnings
        $object = $this->paymentObject;

        $expected = [
            'ACCOUNT.BRAND' => 'EASYCREDIT',
            'ADDRESS.CITY' => $city,
            'ADDRESS.COUNTRY' => $country,
            'ADDRESS.STATE' => $state,
            'ADDRESS.STREET' => $street,
            'ADDRESS.ZIP' => $zip,
            'CONTACT.EMAIL' => $email,
            'CRITERION.PAYMENT_METHOD' => $object::getClassName(),
            'CRITERION.SECRET' => '8263dc9e31b5754332aa23752b0044f4f6ab716ca9ec8d94a1b74ac19d2c5b822d3b' .
                '1a0d8106c93cd02f2f5654d54c510444e310576e5559e3926fbdab0af02b',
            'CRITERION.SDK_NAME' => 'Heidelpay\\PhpPaymentApi',
            'CRITERION.SDK_VERSION' => ApiConfig::SDK_VERSION,
            'FRONTEND.ENABLED' => 'TRUE',
            'FRONTEND.MODE' => 'WHITELABEL',
            'IDENTIFICATION.SHOPPERID' => $shopperId,
            'IDENTIFICATION.TRANSACTIONID' => $timestamp,
            'NAME.GIVEN' => $firstName,
            'NAME.FAMILY' => $lastName,
            'PAYMENT.CODE' => self::PAYMENT_METHOD_SHORT . '.' . TransactionType::INITIALIZE,
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
