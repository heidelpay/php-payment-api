<?php

namespace Heidelpay\Tests\PhpPaymentApi\Integration\PaymentMethods;

use Heidelpay\PhpPaymentApi\Response;
use Heidelpay\PhpPaymentApi\PaymentMethods\CreditCardPaymentMethod;
use Heidelpay\Tests\PhpPaymentApi\Helper\BasePaymentMethodTest;

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
 * @package heidelpay\php-payment-api\tests\integration
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
     * Used to test reregistration
     *
     * @var string Account holder
     */
    protected $holder2 = 'Payment Berger-Heidel';

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
    protected $secret = 'Heidelpay-PhpPaymentApi';

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

    /**
     * PaymentObject
     *
     * @var \Heidelpay\PhpPaymentApi\PaymentMethods\CreditCardPaymentMethod
     */
    protected $paymentObject;

    /**
     * Constructor used to set timezone to utc
     */
    public function __construct()
    {
        date_default_timezone_set('UTC');

        parent::__construct(); // creates authentication and customer data objects as well
    }

    //</editor-fold>

    //<editor-fold desc="Setup">

    /**
     * Set up function will create a credit card object for each test case
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    // @codingStandardsIgnoreStart
    public function _before()
    {
        // @codingStandardsIgnoreEnd
        $this->authentication->setTransactionChannel('31HA07BC8142C5A171744F3D6D155865');

        $CreditCard = new CreditCardPaymentMethod();
        $CreditCard->getRequest()->authentification(...$this->authentication->getAuthenticationArray());
        $CreditCard->getRequest()->customerAddress(...$this->customerData->getCustomerDataArray());
        $CreditCard->getRequest()->getCriterion()->set('TestValue', 'test');
        $CreditCard->dryRun = true;

        $this->paymentObject = $CreditCard;
    }

    //</editor-fold>

    //<editor-fold desc="Tests">

    /**
     * Test case for credit cart registration without payment frame
     *
     * @return string payment reference id to the credit card registration
     *
     * @group  connectionTest
     * @test
     *
     * @throws \Exception
     */
    public function registration()
    {
        $timestamp = $this->getMethod(__METHOD__) . ' ' . date('Y-m-d H:i:s');
        $this->paymentObject->getRequest()->basketData($timestamp, 23.12, $this->currency, $this->secret);

        $this->paymentObject->registration('http://www.heidelpay.de', 'FALSE', 'http://www.heidelpay.de');

        /* disable frontend (iframe) and submit the credit card information directly (only for testing) */
        $this->paymentObject->getRequest()->getFrontend()->setEnabled('FALSE');
        $this->paymentObject->getRequest()->getAccount()->setHolder($this->holder);
        $this->paymentObject->getRequest()->getAccount()->setNumber($this->creditCartNumber);
        $this->paymentObject->getRequest()->getAccount()->set('expiry_month', $this->creditCardExpiryMonth);
        $this->paymentObject->getRequest()->getAccount()->set('expiry_year', $this->creditCardExpiryYear);
        $this->paymentObject->getRequest()->getAccount()->setBrand($this->creditCardBrand);
        $this->paymentObject->getRequest()->getAccount()->set('verification', $this->creditCardVerification);

        /* prepare request and send it to payment api */
        $request = $this->paymentObject->getRequest()->toArray();

        /** @var Response $response */
        list($result, $response) =
            $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);

        $this->assertTrue($response->isSuccess(), 'Transaction failed : ' . print_r($response->getError(), 1));
        $this->assertFalse($response->isPending(), 'registration is pending');
        $this->assertFalse($response->isError(), 'registration failed : ' . print_r($response->getError(), 1));

        $this->logDataToDebug($result);

        return (string)$response->getPaymentReferenceId();
    }

    /**
     * Test case for credit cart reregistration without payment frame
     *
     * @param null $referenceId
     *
     * @return string
     *
     * @throws \Exception
     * @group  connectionTest
     * @depends registration
     * @test
     */
    public function reregistration($referenceId = null)
    {
        $timestamp = $this->getMethod(__METHOD__) . ' ' . date('Y-m-d H:i:s');
        $this->paymentObject->getRequest()->basketData(
            $timestamp,
            23.12,
            $this->currency,
            $this->secret
        );

        $this->paymentObject->reregistration(
            $referenceId,
            'http://www.heidelpay.de',
            'FALSE',
            'http://www.heidelpay.de'
        );

        /* disable frontend (iframe) and submit the credit card information directly (only for testing) */
        $this->paymentObject->getRequest()->getFrontend()->setEnabled('FALSE');
        $this->paymentObject->getRequest()->getAccount()->setHolder($this->holder2);
        $this->paymentObject->getRequest()->getAccount()->setNumber($this->creditCartNumber);
        $this->paymentObject->getRequest()->getAccount()->setBrand($this->creditCardBrand);
        $this->paymentObject->getRequest()->getAccount()->set('expiry_month', $this->creditCardExpiryMonth);
        $this->paymentObject->getRequest()->getAccount()->set('expiry_year', $this->creditCardExpiryYear);
        $this->paymentObject->getRequest()->getAccount()->set('verification', $this->creditCardVerification);

        /* prepare request and send it to payment api */
        $request = $this->paymentObject->getRequest()->toArray();

        /** @var Response $response */
        list($result, $response) =
            $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);

        $this->assertTrue($response->isSuccess(), 'Transaction failed : ' . print_r($response->getError(), 1));
        $this->assertFalse($response->isPending(), 'registration is pending');
        $this->assertFalse($response->isError(), 'registration failed : ' . print_r($response->getError(), 1));

        $this->assertEquals('test', $response->getCriterion()->get('TestValue'));

        $this->logDataToDebug($result);

        return (string)$response->getPaymentReferenceId();
    }

    /**
     * Test case for a credit card debit on a registration
     *
     * @param $referenceId string reference id of the credit card registration
     *
     * @return string payment reference id to the credit card debit transaction
     *
     * @depends registration
     * @group  connectionTest
     * @test
     *
     * @throws \Exception
     */
    public function debitOnRegistration($referenceId = null)
    {
        $timestamp = $this->getMethod(__METHOD__) . ' ' . date('Y-m-d H:i:s');
        $this->paymentObject->getRequest()->basketData($timestamp, 23.12, $this->currency, $this->secret);

        $this->paymentObject->dryRun = false;

        $this->paymentObject->getRequest()->getFrontend()->setEnabled('FALSE');

        $this->paymentObject->debitOnRegistration((string)$referenceId);

        $this->assertTrue(
            $this->paymentObject->getResponse()->isSuccess(),
            'Transaction failed : ' . print_r($this->paymentObject->getResponse()->getError(), 1)
        );
        $this->assertFalse($this->paymentObject->getResponse()->isPending(), 'debit on registration is pending');
        $this->assertFalse(
            $this->paymentObject->getResponse()->isError(),
            'debit on registration failed : ' . print_r($this->paymentObject->getResponse()->getError(), 1)
        );

        $this->logDataToDebug();

        return (string)$this->paymentObject->getResponse()->getPaymentReferenceId();
    }

    /**
     * Test case for credit card authorisation on a registration
     *
     * @param mixed $referenceId reference id of the credit card registration
     *
     * @return string payment reference id of the credit card authorisation
     *
     * @depends registration
     * @group  connectionTest
     * @test
     *
     * @throws \Heidelpay\PhpPaymentApi\Exceptions\UndefinedTransactionModeException
     */
    public function authorizeOnRegistration($referenceId = null)
    {
        $timestamp = $this->getMethod(__METHOD__) . ' ' . date('Y-m-d H:i:s');
        $this->paymentObject->getRequest()->basketData($timestamp, 23.12, $this->currency, $this->secret);

        $this->paymentObject->dryRun = false;

        $this->paymentObject->getRequest()->getFrontend()->setEnabled('FALSE');

        $this->paymentObject->authorizeOnRegistration((string)$referenceId);

        $this->assertTrue(
            $this->paymentObject->getResponse()->isSuccess(),
            'Transaction failed : ' . print_r($this->paymentObject->getResponse()->getError(), 1)
        );
        $this->assertFalse($this->paymentObject->getResponse()->isPending(), 'authorize on registration is pending');
        $this->assertFalse(
            $this->paymentObject->getResponse()->isError(),
            'authorized on registration failed : ' . print_r($this->paymentObject->getResponse()->getError(), 1)
        );

        $this->logDataToDebug();

        return (string)$this->paymentObject->getResponse()->getPaymentReferenceId();
    }

    /**
     * @depends authorizeOnRegistration
     * @test
     *
     * @param mixed $referenceId
     *
     * @return string
     *
     * @throws \Heidelpay\PhpPaymentApi\Exceptions\UndefinedTransactionModeException
     */
    public function capture($referenceId = null)
    {
        $timestamp = $this->getMethod(__METHOD__) . ' ' . date('Y-m-d H:i:s');
        $this->paymentObject->getRequest()->basketData($timestamp, 23.12, $this->currency, $this->secret);

        $this->paymentObject->capture((string)$referenceId);

        /* prepare request and send it to payment api */
        $request = $this->paymentObject->getRequest()->toArray();

        /** @var Response $response */
        list($result, $response) =
            $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);

        $this->assertTrue($response->isSuccess(), 'Transaction failed : ' . print_r($response->getError(), 1));
        $this->assertFalse($response->isPending(), 'capture is pending');
        $this->assertFalse($response->isError(), 'capture failed : ' . print_r($response->getError(), 1));

        $this->logDataToDebug($result);

        return (string)$response->getPaymentReferenceId();
    }

    /**
     * Test case for credit card refund
     *
     * @param $referenceId string reference id of the credit card debit/capture to refund
     *
     * @return string payment reference id of the credit card refund transaction
     *
     * @depends capture
     * @group connectionTest
     * @test
     *
     * @throws \Exception
     */
    public function refund($referenceId = null)
    {
        $timestamp = $this->getMethod(__METHOD__) . ' ' . date('Y-m-d H:i:s');
        $this->paymentObject->getRequest()->basketData($timestamp, 23.12, $this->currency, $this->secret);

        $this->paymentObject->refund((string)$referenceId);

        /* prepare request and send it to payment api */
        $request = $this->paymentObject->getRequest()->toArray();

        /** @var Response $response */
        list($result, $response) =
            $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);

        $this->assertTrue($response->isSuccess(), 'Transaction failed : ' . print_r($response->getError(), 1));
        $this->assertFalse($response->isPending(), 'authorize on registration is pending');
        $this->assertFalse(
            $response->isError(),
            'authorized on registration failed : ' . print_r($response->getError(), 1)
        );

        $this->logDataToDebug($result);

        return (string)$response->getPaymentReferenceId();
    }

    /**
     * Test case for a single credit card debit transaction without payment frame
     *
     * @return string payment reference id for the credit card debit transaction
     *
     * @group connectionTest
     * @test
     *
     * @throws \Exception
     */
    public function debit()
    {
        $timestamp = $this->getMethod(__METHOD__) . ' ' . date('Y-m-d H:i:s');
        $this->paymentObject->getRequest()->basketData($timestamp, 23.12, $this->currency, $this->secret);

        $this->paymentObject->debit('http://www.heidelpay.de', 'FALSE', 'http://www.heidelpay.de');

        /* disable frontend (ifame) and submit the credit card information directly (only for testing) */
        $this->paymentObject->getRequest()->getFrontend()->setEnabled('FALSE');
        $this->paymentObject->getRequest()->getAccount()->setHolder($this->holder);
        $this->paymentObject->getRequest()->getAccount()->setNumber($this->creditCartNumber);
        $this->paymentObject->getRequest()->getAccount()->set('expiry_month', $this->creditCardExpiryMonth);
        $this->paymentObject->getRequest()->getAccount()->set('expiry_year', $this->creditCardExpiryYear);
        $this->paymentObject->getRequest()->getAccount()->setBrand($this->creditCardBrand);
        $this->paymentObject->getRequest()->getAccount()->set('verification', $this->creditCardVerification);

        /* prepare request and send it to payment api */
        $request = $this->paymentObject->getRequest()->toArray();

        /** @var Response $response */
        list($result, $response) =
            $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);

        $this->assertTrue($response->isSuccess(), 'Transaction failed : ' . print_r($response->getError(), 1));
        $this->assertFalse($response->isPending(), 'debit is pending');
        $this->assertFalse($response->isError(), 'debit failed : ' . print_r($response->getError(), 1));

        $this->logDataToDebug($result);

        return (string)$response->getPaymentReferenceId();
    }

    /**
     * Test case for a single credit card authorisation without payment frame
     *
     * @return string payment reference id for the credit card authorize transaction
     *
     * @group connectionTest
     * @test
     *
     * @throws \Exception
     */
    public function authorize()
    {
        $timestamp = $this->getMethod(__METHOD__) . ' ' . date('Y-m-d H:i:s');
        $this->paymentObject->getRequest()->basketData($timestamp, 23.12, $this->currency, $this->secret);

        $this->paymentObject->authorize('http://www.heidelpay.de', 'FALSE', 'http://www.heidelpay.de');

        /* disable frontend (ifame) and submit the credit card information directly (only for testing) */
        $this->paymentObject->getRequest()->getFrontend()->setEnabled('FALSE');
        $this->paymentObject->getRequest()->getAccount()->setHolder($this->holder);
        $this->paymentObject->getRequest()->getAccount()->setNumber($this->creditCartNumber);
        $this->paymentObject->getRequest()->getAccount()->set('expiry_month', $this->creditCardExpiryMonth);
        $this->paymentObject->getRequest()->getAccount()->set('expiry_year', $this->creditCardExpiryYear);
        $this->paymentObject->getRequest()->getAccount()->setBrand($this->creditCardBrand);
        $this->paymentObject->getRequest()->getAccount()->set('verification', $this->creditCardVerification);

        /* prepare request and send it to payment api */
        $request = $this->paymentObject->getRequest()->toArray();

        /** @var Response $response */
        list($result, $response) =
            $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);

        $this->assertTrue($response->isSuccess(), 'Transaction failed : ' . print_r($response->getError(), 1));
        $this->assertFalse($response->isPending(), 'authorize is pending');
        $this->assertFalse($response->isError(), 'authorize failed : ' . print_r($response->getError(), 1));

        $this->logDataToDebug($result);

        return (string)$response->getPaymentReferenceId();
    }

    /**
     * Test case for a credit card reversal of a existing authorization
     *
     * @param mixed $referenceId id of the credit card authorisation
     *
     * @return string payment reference id for the credit card reversal transaction
     *
     * @throws \Exception
     * @depends authorize
     * @group connectionTest
     * @test
     */
    public function reversal($referenceId = null)
    {
        $timestamp = $this->getMethod(__METHOD__) . ' ' . date('Y-m-d H:i:s');
        $this->paymentObject->getRequest()->basketData($timestamp, 2.12, $this->currency, $this->secret);

        $this->paymentObject->reversal((string)$referenceId);

        /* prepare request and send it to payment api */
        $request = $this->paymentObject->getRequest()->toArray();

        /** @var Response $response */
        list($result, $response) =
            $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);

        $this->assertTrue($response->isSuccess(), 'Transaction failed : ' . print_r($response->getError(), 1));
        $this->assertFalse($response->isPending(), 'reversal is pending');
        $this->assertFalse($response->isError(), 'reversal failed : ' . print_r($response->getError(), 1));

        $this->logDataToDebug($result);

        return (string)$response->getPaymentReferenceId();
    }

    /**
     * Test case for a credit card rebill of an existing debit or capture
     *
     * @param mixed $referenceId id of the credit card debit or capture
     *
     * @return string payment reference id for the credit card rebill transaction
     *
     * @throws \Exception
     * @depends debit
     * @group connectionTest
     * @test
     */
    public function rebill($referenceId = null)
    {
        $timestamp = $this->getMethod(__METHOD__) . ' ' . date('Y-m-d H:i:s');
        $this->paymentObject->getRequest()->basketData($timestamp, 2.12, $this->currency, $this->secret);

        $this->paymentObject->rebill((string)$referenceId);

        /* prepare request and send it to payment api */
        $request = $this->paymentObject->getRequest()->toArray();

        /** @var Response $response */
        list($result, $response) =
            $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);

        $this->assertTrue($response->isSuccess(), 'Transaction failed : ' . print_r($response->getError(), 1));
        $this->assertFalse($response->isPending(), 'reversal is pending');
        $this->assertFalse($response->isError(), 'reversal failed : ' . print_r($response->getError(), 1));

        $this->logDataToDebug($result);

        return (string)$response->getPaymentReferenceId();
    }

    //</editor-fold>
}
