<?php

namespace Heidelpay\Tests\PhpApi\Unit\PaymentMethods;

use AspectMock\Proxy\InstanceProxy;
use AspectMock\Test;
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
        Test::clean();
    }

    //</editor-fold>

    //<editor-fold desc="Tests">

    //<editor-fold desc="Common">

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

    //<editor-fold desc="authorize">

    /**
     * Test authorize sets transaction code as expected
     *
     * @test
     */
    public function authorizeShouldSetTransactionCodePA()
    {
        /** @var InstanceProxy $curlMock */
        $curlMock = $this->paymentObject->getAdapter();
        $paymentParameterGroup = $this->paymentObject->getRequest()->getPayment();

        // verify it has no transaction code before
        $payment_code = $paymentParameterGroup->getCode();
        $this->assertCount(1, explode('.', $payment_code));

        $this->paymentObject->authorize();

        $curlMock->verifyInvokedOnce('sendPost');

        // verify the transaction PA has been appended
        $payment_code = $paymentParameterGroup->getCode();
        $this->assertCount(2, explode('.', $payment_code));
        $this->assertEquals('PA', explode('.', $payment_code)[1]);
    }

    /**
     * Test authorize sets frontend as expected
     *
     * @test
     */
    public function authorizeShouldSetFrontendGroupParameters()
    {
        $frontendParameterGroup = $this->paymentObject->getRequest()->getFrontend();

        // verify initial state
        $this->assertEquals('TRUE', $frontendParameterGroup->getEnabled());
        $this->assertNull($frontendParameterGroup->getPaymentFrameOrigin());
        $this->assertNull($frontendParameterGroup->getPreventAsyncRedirect());
        $this->assertNull($frontendParameterGroup->getCssPath());

        $this->paymentObject->authorize('paymentFrameOrigin', 'FALSE', 'cssPath');

        // verify the frontend values changed as expected
        $this->assertEquals('TRUE', $frontendParameterGroup->getEnabled());
        $this->assertEquals('paymentFrameOrigin', $frontendParameterGroup->getPaymentFrameOrigin());
        $this->assertEquals('FALSE', $frontendParameterGroup->getPreventAsyncRedirect());
        $this->assertEquals('cssPath', $frontendParameterGroup->getCssPath());
    }

    /**
     * Verify authorize returns the CreditCardPaymentMethod object
     *
     * @test
     */
    public function authorizeShouldReturnThePaymentMethodObject()
    {
        $object = $this->paymentObject->authorize();

        $this->assertSame($this->paymentObject, $object);
    }

    //</editor-fold>

    //<editor-fold desc="debit">

    /**
     * Test debit sets transaction code as expected
     *
     * @test
     */
    public function debitShouldSetTransactionCodeDB()
    {
        /** @var InstanceProxy $curlMock */
        $curlMock = $this->paymentObject->getAdapter();
        $paymentParameterGroup = $this->paymentObject->getRequest()->getPayment();

        // verify it has no transaction code before
        $payment_code = $paymentParameterGroup->getCode();
        $this->assertCount(1, explode('.', $payment_code));

        $this->paymentObject->debit();

        $curlMock->verifyInvokedOnce('sendPost');

        // verify the transaction PA has been appended
        $payment_code = $paymentParameterGroup->getCode();
        $this->assertCount(2, explode('.', $payment_code));
        $this->assertEquals('DB', explode('.', $payment_code)[1]);
    }

    /**
     * Test debit sets frontend as expected
     *
     * @test
     */
    public function debitShouldSetFrontendGroupParameters()
    {
        $frontendParameterGroup = $this->paymentObject->getRequest()->getFrontend();

        // verify initial state
        $this->assertNull($frontendParameterGroup->getPaymentFrameOrigin());
        $this->assertNull($frontendParameterGroup->getPreventAsyncRedirect());
        $this->assertNull($frontendParameterGroup->getCssPath());

        $this->paymentObject->debit('paymentFrameOrigin', 'FALSE', 'cssPath');

        // verify the frontend values changed as expected
        $this->assertEquals('paymentFrameOrigin', $frontendParameterGroup->getPaymentFrameOrigin());
        $this->assertEquals('FALSE', $frontendParameterGroup->getPreventAsyncRedirect());
        $this->assertEquals('cssPath', $frontendParameterGroup->getCssPath());
    }

    /**
     * Verify debit returns the CreditCardPaymentMethod object
     *
     * @test
     */
    public function debitShouldReturnThePaymentMethodObject()
    {
        $object = $this->paymentObject->debit();

        $this->assertSame($this->paymentObject, $object);
    }

    //</editor-fold>





//    /**
//     * Test case for credit cart registration without payment frame
//     *
//     * @return string payment reference id to the credit card registration
//     * @group  connectionTest
//     * @test
//     */
//    public function registration()
//    {
//        $timestamp = $this->getTimestampString();
//        $this->paymentObject->getRequest()->basketData(
//            $timestamp,
//            23.12,
//            $this->currency,
//            $this->secret
//        );
//
//        $this->paymentObject->registration(
//            'http://www.heidelpay.de',
//            'FALSE',
//            'http://www.heidelpay.de'
//        );
//
//        /* disable frontend (iframe) and submit the credit card information directly (only for testing) */
//        $this->paymentObject->getRequest()->getFrontend()->set('enabled', 'FALSE');
//        $this->paymentObject->getRequest()->getAccount()->set('holder', $this->holder);
//        $this->paymentObject->getRequest()->getAccount()->set('number', $this->creditCartNumber);
//        $this->paymentObject->getRequest()->getAccount()->set('expiry_month', $this->creditCardExpiryMonth);
//        $this->paymentObject->getRequest()->getAccount()->set('expiry_year', $this->creditCardExpiryYear);
//        $this->paymentObject->getRequest()->getAccount()->set('brand', $this->creditCardBrand);
//        $this->paymentObject->getRequest()->getAccount()->set('verification', $this->creditCardVerification);
//
//        /* prepare request and send it to payment api */
//        $request = $this->paymentObject->getRequest()->convertToArray();
//
//        /** @var Response $response */
//        list(, $response) = $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);
//
//        $this->assertTrue($response->isSuccess(), 'Transaction failed : ' . print_r($response->getError(), 1));
//        $this->assertFalse($response->isPending(), 'registration is pending');
//        $this->assertFalse($response->isError(), 'registration failed : ' . print_r($response->getError(), 1));
//
//        return (string)$response->getPaymentReferenceId();
//    }
//
//    /**
//     * Test case for a credit card debit on a registration
//     *
//     * @param $referenceId string reference id of the credit card registration
//     *
//     * @return string payment reference id to the credit card debit transaction
//     * @depends registration
//     * @group  connectionTest
//     * @test
//     */
//    public function debitOnRegistration($referenceId = null)
//    {
//        $timestamp = $this->getTimestampString();
//        $this->paymentObject->getRequest()->basketData($timestamp, 23.12, $this->currency, $this->secret);
//
//        $this->paymentObject->_dryRun = false;
//
//        $this->paymentObject->getRequest()->getFrontend()->set('enabled', 'FALSE');
//
//        $this->paymentObject->debitOnRegistration((string)$referenceId);
//
//        $this->assertTrue(
//            $this->paymentObject->getResponse()->isSuccess(),
//            'Transaction failed : ' . print_r($this->paymentObject->getResponse()->getError(), 1)
//        );
//        $this->assertFalse($this->paymentObject->getResponse()->isPending(), 'debit on registration is pending');
//        $this->assertFalse(
//            $this->paymentObject->getResponse()->isError(),
//            'debit on registration failed : ' . print_r($this->paymentObject->getResponse()->getError(), 1)
//        );
//
//        return (string)$this->paymentObject->getResponse()->getPaymentReferenceId();
//    }
//
//    /**
//     * Test case for credit card authorisation on a registration
//     *
//     * @param referenceId string reference id of the credit card registration
//     * @param mixed $referenceId
//     *
//     * @return string payment reference id of the credit card authorisation
//     * @depends registration
//     * @group  connectionTest
//     * @test
//     */
//    public function authorizeOnRegistration($referenceId = null)
//    {
//        $timestamp = $this->getTimestampString();
//        $this->paymentObject->getRequest()->basketData($timestamp, 23.12, $this->currency, $this->secret);
//
//        $this->paymentObject->_dryRun = false;
//
//        $this->paymentObject->getRequest()->getFrontend()->set('enabled', 'FALSE');
//
//        $this->paymentObject->authorizeOnRegistration((string)$referenceId);
//
//        $this->assertTrue(
//            $this->paymentObject->getResponse()->isSuccess(),
//            'Transaction failed : ' . print_r($this->paymentObject->getResponse()->getError(), 1)
//        );
//        $this->assertFalse($this->paymentObject->getResponse()->isPending(), 'authorize on registration is pending');
//        $this->assertFalse(
//            $this->paymentObject->getResponse()->isError(),
//            'authorized on registration failed : ' . print_r($this->paymentObject->getResponse()->getError(), 1)
//        );
//
//        return (string)$this->paymentObject->getResponse()->getPaymentReferenceId();
//    }
//
//    /**
//     * @depends authorizeOnRegistration
//     * @test
//     *
//     * @param mixed $referenceId
//     *
//     * @return string
//     */
//    public function capture($referenceId = null)
//    {
//        $timestamp = $this->getTimestampString();
//        $this->paymentObject->getRequest()->basketData($timestamp, 23.12, $this->currency, $this->secret);
//
//        $this->paymentObject->capture((string)$referenceId);
//
//        /* prepare request and send it to payment api */
//        $request = $this->paymentObject->getRequest()->convertToArray();
//
//        /** @var Response $response */
//        list(, $response) = $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);
//
//        $this->assertTrue($response->isSuccess(), 'Transaction failed : ' . print_r($response->getError(), 1));
//        $this->assertFalse($response->isPending(), 'capture is pending');
//        $this->assertFalse($response->isError(), 'capture failed : ' . print_r($response->getError(), 1));
//
//        return (string)$response->getPaymentReferenceId();
//    }
//
//    /**
//     * Test case for credit card refund
//     *
//     * @param $referenceId string reference id of the credit card debit/capture to refund
//     *
//     * @return string payment reference id of the credit card refund transaction
//     * @depends capture
//     * @group connectionTest
//     * @test
//     */
//    public function refund($referenceId = null)
//    {
//        $timestamp = $this->getTimestampString();
//        $this->paymentObject->getRequest()->basketData($timestamp, 23.12, $this->currency, $this->secret);
//
//        $this->paymentObject->refund((string)$referenceId);
//
//        /* prepare request and send it to payment api */
//        $request = $this->paymentObject->getRequest()->convertToArray();
//        /** @var Response $response */
//        list(, $response) = $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);
//
//        $this->assertTrue($response->isSuccess(), 'Transaction failed : ' . print_r($response->getError(), 1));
//        $this->assertFalse($response->isPending(), 'authorize on registration is pending');
//        $this->assertFalse(
//            $response->isError(),
//            'authorized on registration failed : ' . print_r($response->getError(), 1)
//        );
//
//        return (string)$response->getPaymentReferenceId();
//    }
//
//    /**
//     * Test case for a single credit card debit transaction without payment frame
//     *
//     * @return string payment reference id for the credit card debit transaction
//     * @group connectionTest
//     * @test
//     */
//    public function debit()
//    {
//        $timestamp = $this->getTimestampString();
//        $this->paymentObject->getRequest()->basketData($timestamp, 23.12, $this->currency, $this->secret);
//
//        $this->paymentObject->debit('http://www.heidelpay.de', 'FALSE', 'http://www.heidelpay.de');
//
//        /* disable frontend (ifame) and submit the credit card information directly (only for testing) */
//        $this->paymentObject->getRequest()->getFrontend()->set('enabled', 'FALSE');
//        $this->paymentObject->getRequest()->getAccount()->set('holder', $this->holder);
//        $this->paymentObject->getRequest()->getAccount()->set('number', $this->creditCartNumber);
//        $this->paymentObject->getRequest()->getAccount()->set('expiry_month', $this->creditCardExpiryMonth);
//        $this->paymentObject->getRequest()->getAccount()->set('expiry_year', $this->creditCardExpiryYear);
//        $this->paymentObject->getRequest()->getAccount()->set('brand', $this->creditCardBrand);
//        $this->paymentObject->getRequest()->getAccount()->set('verification', $this->creditCardVerification);
//
//        /* prepare request and send it to payment api */
//        $request = $this->paymentObject->getRequest()->convertToArray();
//        /** @var Response $response */
//        list(, $response) = $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);
//
//        $this->assertTrue($response->isSuccess(), 'Transaction failed : ' . print_r($response->getError(), 1));
//        $this->assertFalse($response->isPending(), 'debit is pending');
//        $this->assertFalse($response->isError(), 'debit failed : ' . print_r($response->getError(), 1));
//
//        return (string)$response->getPaymentReferenceId();
//    }
//
//    /**
//     * Test case for a single credit card authorisation without payment frame
//     *
//     * @return string payment reference id for the credit card authorize transaction
//     * @group connectionTest
//     * @test
//     */
//    public function authorize()
//    {
//        $timestamp = $this->getTimestampString();
//        $this->paymentObject->getRequest()->basketData($timestamp, 23.12, $this->currency, $this->secret);
//
//        $this->paymentObject->authorize('http://www.heidelpay.de', 'FALSE', 'http://www.heidelpay.de');
//
//        /* disable frontend (ifame) and submit the credit card information directly (only for testing) */
//        $this->paymentObject->getRequest()->getFrontend()->set('enabled', 'FALSE');
//        $this->paymentObject->getRequest()->getAccount()->set('holder', $this->holder);
//        $this->paymentObject->getRequest()->getAccount()->set('number', $this->creditCartNumber);
//        $this->paymentObject->getRequest()->getAccount()->set('expiry_month', $this->creditCardExpiryMonth);
//        $this->paymentObject->getRequest()->getAccount()->set('expiry_year', $this->creditCardExpiryYear);
//        $this->paymentObject->getRequest()->getAccount()->set('brand', $this->creditCardBrand);
//        $this->paymentObject->getRequest()->getAccount()->set('verification', $this->creditCardVerification);
//
//        /* prepare request and send it to payment api */
//        $request = $this->paymentObject->getRequest()->convertToArray();
//        /** @var Response $response */
//        list(, $response) = $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);
//
//        $this->assertTrue($response->isSuccess(), 'Transaction failed : ' . print_r($response->getError(), 1));
//        $this->assertFalse($response->isPending(), 'authorize is pending');
//        $this->assertFalse($response->isError(), 'authorize failed : ' . print_r($response->getError(), 1));
//
//        return (string)$response->getPaymentReferenceId();
//    }
//
//    /**
//     * Test case for a credit card reversal of a existing authorisation
//     *
//     * @var string payment reference id of the credit card authorisation
//     *
//     * @return string payment reference id for the credit card reversal transaction
//     * @depends authorize
//     * @group connectionTest
//     * @test
//     *
//     * @param mixed $referenceId
//     */
//    public function reversal($referenceId = null)
//    {
//        $timestamp = $this->getTimestampString();
//        $this->paymentObject->getRequest()->basketData($timestamp, 2.12, $this->currency, $this->secret);
//
//        $this->paymentObject->reversal((string)$referenceId);
//
//        /* prepare request and send it to payment api */
//        $request = $this->paymentObject->getRequest()->convertToArray();
//        /** @var Response $response */
//        list(, $response) = $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);
//
//        $this->assertTrue($response->isSuccess(), 'Transaction failed : ' . print_r($response->getError(), 1));
//        $this->assertFalse($response->isPending(), 'reversal is pending');
//        $this->assertFalse($response->isError(), 'reversal failed : ' . print_r($response->getError(), 1));
//
//        return (string)$response->getPaymentReferenceId();
//    }
//
//    /**
//     * Test case for a credit card rebill of an existing debit or capture
//     *
//     * @var string payment reference id of the credit card debit or capture
//     *
//     * @return string payment reference id for the credit card rebill transaction
//     * @depends debit
//     * @group connectionTest
//     * @test
//     *
//     * @param mixed $referenceId
//     */
//    public function rebill($referenceId = null)
//    {
//        $timestamp = $this->getTimestampString();
//        $this->paymentObject->getRequest()->basketData($timestamp, 2.12, $this->currency, $this->secret);
//
//        $this->paymentObject->rebill((string)$referenceId);
//
//        /* prepare request and send it to payment api */
//        $request = $this->paymentObject->getRequest()->convertToArray();
//        /** @var Response $response */
//        list(, $response) = $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);
//
//        $this->assertTrue($response->isSuccess(), 'Transaction failed : ' . print_r($response->getError(), 1));
//        $this->assertFalse($response->isPending(), 'reversal is pending');
//        $this->assertFalse($response->isError(), 'reversal failed : ' . print_r($response->getError(), 1));
//
//        return (string)$response->getPaymentReferenceId();
//    }



    //</editor-fold>
}
