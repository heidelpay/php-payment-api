<?php

namespace Heidelpay\Tests\PhpPaymentApi\Integration\PaymentMethods;

use Heidelpay\PhpPaymentApi\Response;
use Heidelpay\PhpPaymentApi\PaymentMethods\DebitCardPaymentMethod as DebitCard;
use Heidelpay\Tests\PhpPaymentApi\Helper\BasePaymentMethodTest;

/**
 * Debit card test
 *
 * Connection tests can fail due to network issues and scheduled down times.
 * This does not have to mean that your integration is broken. Please verify the given debug information
 *
 *  Warning:
 *  - Use of the following code is only allowed with this sandbox debit card information.
 *
 *  - Using this code or even parts of it with real debit card information  is a violation
 *  of the payment card industry standard aka pci3.
 *
 *  - You are not allowed to save, store and/or process debit card information any time with your systems.
 *    Always use Heidelpay payment frame solution for a pci3 conform debit card integration.
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
class DebitCardPaymentMethodTest extends BasePaymentMethodTest
{
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
     * Debit card number
     * Do not use real debit card information for this test. For more details read the information
     * on top of this test class.
     *
     * @var string debit card number
     */
    protected $creditCartNumber = '4711100000000000';
    /**
     * Debit card brand
     * Do not use real debit card information for this test. For more details read the information
     * on top of this test class.
     *
     * @var string debit card brand
     */
    protected $creditCardBrand = 'VISAELECTRON';
    /**
     * Debit card verification
     * Do not use real debit card information for this test. For more details read the information
     * on top of this test class.
     *
     * @var string debit card verification
     */
    protected $creditCardVerification = '123';
    /**
     * Debit card expiry month
     *
     * @var string debit card verification
     */
    protected $creditCardExpiryMonth = '04';
    /**
     * Debit card expiry year
     *
     * @var string debit card year
     */
    protected $creditCardExpiryYear = '2040';
    /**
     * PaymentObject
     *
     * @var \Heidelpay\PhpPaymentApi\PaymentMethods\DebitCardPaymentMethod
     */
    protected $paymentObject;

    /**
     * Constructor used to set timezone to utc
     */
    public function __construct()
    {
        date_default_timezone_set('UTC');

        parent::__construct();
    }

    /**
     * Set up function will create a debit card object for each test case
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    // @codingStandardsIgnoreStart
    public function _before()
    {
        // @codingStandardsIgnoreEnd
        $authentication = $this->authentication
            ->setTransactionChannel('31HA07BC8142C5A171744F3D6D155865')
            ->getAuthenticationArray();
        $customerDetails = $this->customerData->getCustomerDataArray();

        $DebitCard = new DebitCard();
        $DebitCard->getRequest()->authentification(...$authentication);
        $DebitCard->getRequest()->customerAddress(...$customerDetails);
        $DebitCard->dryRun = true;

        $this->paymentObject = $DebitCard;
    }

    /**
     * Test case for debit cart registration without payment frame
     *
     * @return string payment reference id to the credit card registration
     *
     * @group  connectionTest
     *
     * @test
     *
     * @throws \Exception
     */
    public function registration()
    {
        $timestamp = $this->getMethod(__METHOD__) . ' ' . date('Y-m-d H:i:s');
        $this->paymentObject->getRequest()->basketData($timestamp, 23.12, $this->currency, $this->secret);

        $this->paymentObject->registration('http://www.heidelpay.de', 'FALSE', 'http://www.heidelpay.de');

        /* disable frontend (ifame) and submit the debit card information directly (only for testing) */
        $this->paymentObject->getRequest()->getFrontend()->setEnabled('FALSE');
        $this->paymentObject->getRequest()->getAccount()->setHolder($this->holder);
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

        $this->logDataToDebug($result);

        return (string)$response->getPaymentReferenceId();
    }

    /**
     * Test case for debit cart reregistration without payment frame
     *
     * @depends registration
     * @group  connectionTest
     * @test
     *
     * @param null $referenceId
     *
     * @throws \Exception
     */
    public function reregistration($referenceId = null)
    {
        $timestamp = $this->getMethod(__METHOD__) . ' ' . date('Y-m-d H:i:s');
        $this->paymentObject->getRequest()->basketData($timestamp, 23.12, $this->currency, $this->secret);

        $this->paymentObject->reregistration(
            $referenceId,
            'http://www.heidelpay.de',
            'FALSE',
            'http://www.heidelpay.de'
        );

        /* disable frontend (ifame) and submit the debit card information directly (only for testing) */
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

        $this->logDataToDebug($result);
    }

    /**
     * Test case for a debit card debit on a registration
     *
     * @param mixed $referenceId id of the debit card registration
     *
     * @return string payment reference id to the debit card debit transaction
     *
     * @throws \Exception
     * @depends registration
     * @group  connectionTest
     *
     * @test
     */
    public function debitOnRegistration($referenceId = null)
    {
        $timestamp = $this->getMethod(__METHOD__) . ' ' . date('Y-m-d H:i:s');
        $this->paymentObject->getRequest()->basketData($timestamp, 23.12, $this->currency, $this->secret);

        $this->paymentObject->getRequest()->getFrontend()->setEnabled('FALSE');

        $this->paymentObject->debitOnRegistration((string)$referenceId);

        /* prepare request and send it to payment api */
        $request = $this->paymentObject->getRequest()->toArray();

        /** @var Response $response */
        list($result, $response) =
            $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);

        $this->assertTrue($response->isSuccess(), 'Transaction failed : ' . print_r($response->getError(), 1));
        $this->assertFalse($response->isPending(), 'debit on registration is pending');
        $this->assertFalse(
            $response->isError(),
            'debit on registration failed : ' . print_r($response->getError(), 1)
        );

        $this->logDataToDebug($result);

        return (string)$response->getPaymentReferenceId();
    }

    /**
     * Test case for a debit card authorisation on a registration
     *
     * @param $referenceId string reference id of the debit card registration
     *
     * @return string payment reference id of the debit card authorisation
     *
     * @depends registration
     * @group  connectionTest
     *
     * @test
     *
     * @throws \Exception
     */
    public function authorizeOnRegistration($referenceId = null)
    {
        $timestamp = $this->getMethod(__METHOD__) . ' ' . date('Y-m-d H:i:s');
        $this->paymentObject->getRequest()->basketData($timestamp, 23.12, $this->currency, $this->secret);

        $this->paymentObject->getRequest()->getFrontend()->setEnabled('FALSE');

        $this->paymentObject->authorizeOnRegistration((string)$referenceId);

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
     * @depends authorizeOnRegistration
     * @test
     *
     * @param $referenceId string
     *
     * @return string
     *
     * @throws \Exception
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
     * Test case for a debit card refund
     *
     * @param $referenceId string reference id of the debit card debit/capture to refund
     *
     * @return string payment reference id of the debit card refund transaction
     * @depends capture
     * @test
     *
     * @group connectionTest
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
     * Test case for a single debit card debit transaction without payment frame
     *
     * @return string payment reference id for the debit card debit transaction
     * @group connectionTest
     *
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
     * Test case for a single debit card authorisation without payment frame
     *
     * @return string payment reference id for the debit card authorize transaction
     * @group connectionTest
     *
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
        $this->assertFalse($response->isPending(), 'authorize is pending');
        $this->assertFalse($response->isError(), 'authorize failed : ' . print_r($response->getError(), 1));

        $this->logDataToDebug($result);

        return (string)$response->getPaymentReferenceId();
    }

    /**
     * Test case for a debit card reversal of a existing authorisation
     *
     * @param $referenceId string payment reference id of the debit card authorisation
     *
     * @return string payment reference id for the debit card reversal transaction
     * @depends authorize
     * @group connectionTest
     *
     * @test
     *
     * @throws \Exception
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
     * Test case for a debit card rebill of a existing debit or capture
     *
     * @param string $referenceId payment reference id of the debit card debit or capture
     *
     * @return string payment reference id for the debit card rebill transaction
     * @depends debit
     * @test
     *
     * @group connectionTest
     *
     * @throws \Exception
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
}
