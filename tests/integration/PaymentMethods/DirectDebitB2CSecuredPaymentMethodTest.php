<?php

namespace Heidelpay\Tests\PhpPaymentApi\Integration\PaymentMethods;

use Heidelpay\PhpPaymentApi\Response;
use Heidelpay\PhpPaymentApi\PaymentMethods\DirectDebitB2CSecuredPaymentMethod as DirectDebitSecured;
use Heidelpay\Tests\PhpPaymentApi\Helper\BasePaymentMethodTest;

/**
 * Direct debit Test
 *
 * Connection tests can fail due to network issues and scheduled downtime.
 * This does not have to mean that your integration is broken. Please verify the given debug information
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author  Jens Richter
 *
 * @package heidelpay\php-payment-api\tests\integration
 */
class DirectDebitB2CSecuredPaymentMethodTest extends BasePaymentMethodTest
{
    /**
     * customer mail address
     *
     * @var string contactMail
     */
    protected $iban = 'DE89370400440532013000';

    /**
     * customer mail address
     *
     * @var string contactMail
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
     * PaymentObject
     *
     * @var DirectDebitSecured
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
     * Set up function will create a direct debit object for each test case
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    // @codingStandardsIgnoreStart
    public function _before()
    {
        // @codingStandardsIgnoreEnd
        $authentication = $this->authentication
            ->setTransactionChannel('31HA07BC81856CAD6D8E0B3A3100FBA3')
            ->getAuthenticationArray();
        $customerDetails = $this->customerData->getCustomerDataArray();

        $DirectDebitSecured = new DirectDebitSecured();
        $DirectDebitSecured->getRequest()->authentification(...$authentication);
        $DirectDebitSecured->getRequest()->customerAddress(...$customerDetails);
        $DirectDebitSecured->getRequest()->b2cSecured('MR', '1982-07-12');
        $DirectDebitSecured->dryRun = true;

        $this->paymentObject = $DirectDebitSecured;
    }

    /**
     * Test case for a single direct debit authorize
     *
     * @return string payment reference id for the direct debit transaction
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
        $this->paymentObject->getRequest()->async('DE', 'https://dev.heidelpay.com');
        $this->paymentObject->getRequest()->getFrontend()->setEnabled('FALSE');

        $this->paymentObject->getRequest()->getAccount()->setIban($this->iban);
        $this->paymentObject->getRequest()->getAccount()->setHolder($this->holder);

        $this->paymentObject->authorize();

        /* prepare request and send it to payment api */
        $request = $this->paymentObject->getRequest()->toArray();
        /** @var Response $response */
        list($result, $response) =
            $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);

        $this->assertTrue($response->isSuccess(), 'Transaction failed : ' . print_r($response, 1));
        $this->assertFalse($response->isError(), 'authorize failed : ' . print_r($response->getError(), 1));

        $this->logDataToDebug($result);

        return (string)$response->getPaymentReferenceId();
    }

    /**
     * Test case for a direct debit reversal of a existing authorisation
     *
     * @param string $referenceId payment reference id of the direct debit authorisation
     *
     * @return string payment reference id for the credit card reversal transaction
     * @depends authorize
     * @test
     *
     * @group connectionTest
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
     * Capture Test
     *
     * @depends authorize
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
        $this->paymentObject->getRequest()->basketData($timestamp, 11.00, $this->currency, $this->secret);

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
     * Test case for a single direct debit debit
     *
     * @return string payment reference id for the direct debit transaction
     * @group connectionTest
     *
     * @test
     *
     * @throws \Exception
     */
    public function debit()
    {
        $timestamp = $this->getMethod(__METHOD__) . ' ' . date('Y-m-d H:i:s');
        $this->paymentObject->getRequest()->basketData($timestamp, 13.42, $this->currency, $this->secret);
        $this->paymentObject->getRequest()->async('DE', 'https://dev.heidelpay.com');
        $this->paymentObject->getRequest()->getFrontend()->setEnabled('FALSE');

        $this->paymentObject->getRequest()->getAccount()->setIban($this->iban);
        $this->paymentObject->getRequest()->getAccount()->setHolder($this->holder);

        $this->paymentObject->debit();

        /* prepare request and send it to payment api */
        $request = $this->paymentObject->getRequest()->toArray();
        /** @var Response $response */
        list($result, $response) =
            $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);

        $this->assertTrue($response->isSuccess(), 'Transaction failed : ' . print_r($response, 1));
        $this->assertFalse($response->isError(), 'authorize failed : ' . print_r($response->getError(), 1));

        $this->logDataToDebug($result);

        return (string)$response->getPaymentReferenceId();
    }

    /**
     * Test case for direct debit refund
     *
     * @param string $referenceId reference id of the direct debit to refund
     *
     * @return string payment reference id of the direct debit refund transaction
     * @depends debit
     * @test
     *
     * @group connectionTest
     *
     * @throws \Exception
     */
    public function refund($referenceId = null)
    {
        $timestamp = $this->getMethod(__METHOD__) . ' ' . date('Y-m-d H:i:s');
        $this->paymentObject->getRequest()->basketData($timestamp, 3.54, $this->currency, $this->secret);

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
     * Test case for a direct debit registration
     *
     * @return string payment reference id for the direct debit transaction
     * @group connectionTest
     *
     * @test
     *
     * @throws \Exception
     */
    public function registration()
    {
        $timestamp = $this->getMethod(__METHOD__) . ' ' . date('Y-m-d H:i:s');
        $this->paymentObject->getRequest()->basketData($timestamp, 13.42, $this->currency, $this->secret);
        $this->paymentObject->getRequest()->async('DE', 'https://dev.heidelpay.com');
        $this->paymentObject->getRequest()->getFrontend()->setEnabled('FALSE');

        $this->paymentObject->getRequest()->getAccount()->setIban($this->iban);
        $this->paymentObject->getRequest()->getAccount()->setHolder($this->holder);

        $this->paymentObject->registration();

        /* prepare request and send it to payment api */
        $request = $this->paymentObject->getRequest()->toArray();
        /** @var Response $response */
        list($result, $response) =
            $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);

        $this->assertTrue($response->isSuccess(), 'Transaction failed : ' . print_r($response, 1));
        $this->assertFalse($response->isError(), 'authorize failed : ' . print_r($response->getError(), 1));

        $this->logDataToDebug($result);

        return (string)$response->getPaymentReferenceId();
    }

    /**
     * Test case for a direct debit reregistration
     *
     * @param null $referenceId
     *
     * @return string payment reference id for the direct debit transaction
     *
     * @throws \Exception
     * @depends registration
     * @group connectionTest
     *
     * @test
     */
    public function reregistration($referenceId = null)
    {
        $timestamp = $this->getMethod(__METHOD__) . ' ' . date('Y-m-d H:i:s');
        $this->paymentObject->getRequest()->basketData($timestamp, 13.42, $this->currency, $this->secret);
        $this->paymentObject->getRequest()->async('DE', 'https://dev.heidelpay.com');
        $this->paymentObject->getRequest()->getFrontend()->setEnabled('FALSE');
        $this->paymentObject->getRequest()->getAccount()->setIban($this->iban);
        $this->paymentObject->getRequest()->getAccount()->setHolder($this->holder2);

        $this->paymentObject->reregistration($referenceId);

        /* prepare request and send it to payment api */
        $request = $this->paymentObject->getRequest()->toArray();
        /** @var Response $response */
        list($result, $response) =
            $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);

        $this->assertTrue($response->isSuccess(), 'Transaction failed : ' . print_r($response, 1));
        $this->assertFalse($response->isError(), 'authorize failed : ' . print_r($response->getError(), 1));

        $this->logDataToDebug($result);
    }

    /**
     * Test case for a direct debit rebill of an existing debit or capture
     *
     * @param $referenceId string payment reference id of the direct debit debit or capture
     *
     * @return string payment reference id for the direct debit rebill transaction
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
        $this->paymentObject->getRequest()->basketData($timestamp, 12.12, $this->currency, $this->secret);

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

    /**
     * Test case for direct debit authorisation on a registration
     *
     * @param $referenceId string reference id of the direct debit registration
     *
     * @return string payment reference id of the direct debit authorisation
     * @depends registration
     * @test
     *
     * @group connectionTest
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
     * Test case for direct debit on a registration
     *
     * @param $referenceId string reference id of the direct debit registration
     *
     * @return string payment reference id of the direct debit registration
     * @depends registration
     * @test
     *
     * @group connectionTest
     *
     * @throws \Exception
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
        $this->assertFalse($response->isPending(), 'authorize on registration is pending');
        $this->assertFalse(
            $response->isError(),
            'authorized on registration failed : ' . print_r($response->getError(), 1)
        );

        $this->logDataToDebug($result);

        return (string)$response->getPaymentReferenceId();
    }
}
