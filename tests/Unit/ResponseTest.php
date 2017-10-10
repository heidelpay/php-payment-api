<?php

namespace Heidelpay\Tests\PhpApi\Unit;

use PHPUnit\Framework\TestCase;
use Heidelpay\PhpApi\Response;
use Heidelpay\PhpApi\Exceptions\PaymentFormUrlException;
use Heidelpay\PhpApi\Exceptions\HashVerificationException;

/**
 *
 *  This unit test will cover the response object.
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
class ResponseTest extends TestCase
{
    /**
     * @var \Heidelpay\PhpApi\Response
     */
    protected $responseObject;

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
     * setUp sample response Object
     *
     * {@inheritDoc}
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
        $responseSample = array(
            'NAME_FAMILY' => 'Berger-Payment',
            'IDENTIFICATION_TRANSACTIONID' => '2843294932',
            'ADDRESS_COUNTRY' => 'DE',
            'ADDRESS_STREET' => 'Vagerowstr. 18',
            'FRONTEND_ENABLED' => true,
            'PRESENTATION_AMOUNT' => 23.12,
            'TRANSACTION_MODE' => 'CONNECTOR_TEST',
            'ACCOUNT_EXPIRY_MONTH' => '05',
            'PROCESSING_TIMESTAMP' => '2016-09-16 12:14:31',
            'CONTACT_EMAIL' => 'development@heidelpay.de',
            'FRONTEND_RESPONSE_URL' => 'http://dev.heidelpay.de/response.php',
            'REQUEST_VERSION' => 1.0,
            'ACCOUNT_BRAND' => 'MASTER',
            'PROCESSING_STATUS_CODE' => '90',
            'NAME_GIVEN' => 'Heidel',
            'FRONTEND_PAYMENT_FRAME_ORIGIN' => 'http://dev.heidelpay.de/',
            'IDENTIFICATION_SHORTID' => '3379.5447.1520',
            'ADDRESS_CITY' => 'Heidelberg',
            'ACCOUNT_HOLDER' => 'Heidel Berger-Payment',
            'PROCESSING_CONFIRMATION_STATUS' => 'CONFIRMED',
            'PROCESSING_CODE' => 'CC.RG.90.00',
            'PROCESSING_STATUS' => 'NEW',
            'SECURITY_SENDER' => '31HA07BC8142C5A171745D00AD63D182',
            'USER_LOGIN' => '31ha07bc8142c5a171744e5aef11ffd3',
            'USER_PWD' => '93167DE7',
            'IDENTIFICATION_SHOPPERID' => '12344',
            'PROCESSING_RETURN_CODE' => '000.100.112',
            'PROCESSING_RESULT' => 'ACK',
            'FRONTEND_MODE' => 'WHITELABEL',
            'IDENTIFICATION_UNIQUEID' => '31HA07BC8108A9126F199F2784552637',
            'CRITERION_SECRET' => '209022666cd4706e5f451067592b6be1aff4a913d5bb7f8249f7418ee25c91b318ebac66f41a6692539c8923adfdad6aae26138b1b3a7e37a197ab952be57876',
            'ACCOUNT_EXPIRY_YEAR' => '2018',
            'PRESENTATION_CURRENCY' => 'EUR',
            'PROCESSING_REASON_CODE' => '00',
            'ACCOUNT_VERIFICATION' => '***',
            'ADDRESS_STATE' => 'DE-BW',
            'ADDRESS_ZIP' => '69115',
            'ACCOUNT_NUMBER' => '545301******9543',
            'FRONTEND_PREVENT_ASYNC_REDIRECT' => false,
            'PROCESSING_REASON' => 'SUCCESSFULL',
            'PROCESSING_RETURN' => "Request successfully processed in 'Merchant in Connector Test Mode'",
            'TRANSACTION_CHANNEL' => '31HA07BC8142C5A171744F3D6D155865',
            'FRONTEND_LANGUAGE' => 'DE',
            'PAYMENT_CODE' => 'CC.RG',
            'BASKET_ID' => '31HA07BC8129FBB819367B2205CD6FB4',
            'RISKINFORMATION_SINCE' => '2017-01-01',
            'RISKINFORMATION_ORDERCOUNT' => '5',
            'RISKINFORMATION_GUESTCHECKOUT' => 'FALSE',
        );

        $this->responseObject = new Response($responseSample);
    }

    /**
     *
     * @group integrationTest
     * @test
     */
    public function isSuccess()
    {
        $this->assertTrue($this->responseObject->isSuccess(), 'isSuccess should be true');
        $this->responseObject->getProcessing()->set('result', 'NOK');
        $this->assertFalse($this->responseObject->isSuccess(), 'isSuccess should be false.');
    }

    /**
     * function test for isPending method
     *
     * @group integrationTest
     * @test
     */
    public function isPending()
    {
        $this->assertFalse($this->responseObject->isPending(), 'isPending should be false');
        $this->responseObject->getProcessing()->set('status_code', '80');
        $this->assertTrue($this->responseObject->isPending(), 'isPending should be true');
    }

    /**
     * function test for isError method
     *
     * @group integrationTest
     * @test
     */
    public function isError()
    {
        $this->assertFalse($this->responseObject->isError(), 'isError should be false');
        $this->responseObject->getProcessing()->set('result', 'NOK');
        $this->assertTrue($this->responseObject->isError(), 'isError should be true');
    }

    /**
     * function test for getError method
     *
     * @group integrationTest
     * @test
     */
    public function getError()
    {
        $expectedError = array(
            'code' => '000.100.112',
            'message' => "Request successfully processed in 'Merchant in Connector Test Mode'"
        );

        $this->assertEquals($expectedError, $this->responseObject->getError());
    }

    /**
     * function test for getPaymentReferenceID method
     *
     * @group integrationTest
     * @test
     */
    public function getPaymentReferenceId()
    {
        $this->assertEquals('31HA07BC8108A9126F199F2784552637', $this->responseObject->getPaymentReferenceId());
    }

    /**
     * function test for getPaymentFormUrl method
     *
     * @group integrationTest
     * @test
     */
    public function getPaymentFormUrl()
    {
        /** iframe url for credit and debit card*/
        $expectedUrl = 'http://dev.heidelpay.de';
        $this->responseObject->getFrontend()->set('payment_frame_url', $expectedUrl);
        $this->assertEquals($expectedUrl, $this->responseObject->getPaymentFormUrl());

        $expectedUrl = 'http://www.heidelpay.de';
        $this->responseObject->getFrontend()->set('redirect_url', $expectedUrl);

        /** url in case of credit and debit card refernce Transaction */
        $this->responseObject->getIdentification()->set('referenceid', '31HA07BC8108A9126F199F2784552637');
        $this->assertEquals($expectedUrl, $this->responseObject->getPaymentFormUrl());

        /** unset reference id */
        $this->responseObject->getIdentification()->set('referenceid', null);

        /** url for non credit or debit card transactions */
        $this->responseObject->getPayment()->set('code', 'OT.PA');
        $this->responseObject->getFrontend()->set('redirect_url', $expectedUrl);
        $this->assertEquals($expectedUrl, $this->responseObject->getPaymentFormUrl());
    }

    /**
     * PaymentFormUrlPaymentCodeException test
     *
     * @group integrationTest
     * @test
     */
    public function getPaymentFormUrlPaymentCodeException()
    {
        $response = new Response();

        $response->getFrontend()->set('redirect_url', null);
        $this->expectException(PaymentFormUrlException::class);
        $response->getPaymentFormUrl();
    }

    /**
     * PaymentFormUrlException test
     *
     * @group integrationTest
     * @test
     */
    public function getPaymentFormUrlException()
    {
        $response = new Response();

        $response->getPayment()->set('code', 'OT.PA');
        $response->getFrontend()->set('redirect_url', null);
        $this->expectException(PaymentFormUrlException::class);
        $response->getPaymentFormUrl();
    }

    /**
     * function test for verifySecurityHashUndefiledParameter
     *
     * @group integrationTest
     * @test
     */
    public function verifySecurityHashUndefiledParameter()
    {
        $response = new Response();
        $this->expectException(HashVerificationException::class);
        $response->verifySecurityHash();
    }

    /**
     * function test for verifySecurityHashEmptyResponse
     *
     * @group integrationTest
     * @test
     */
    public function verifySecurityHashEmptyResponse()
    {
        $response = new Response();
        $this->expectException(HashVerificationException::class);
        $response->verifySecurityHash($this->secret, 'Order 12345');
    }

    /**
     * function test for verifySecurityHash empty
     *
     * @group integrationTest
     * @test
     */
    public function verifySecurityHashEmpty()
    {
        $response = new Response();
        $response->getProcessing()->set('result', 'ACK');
        $this->expectException(HashVerificationException::class);
        $response->verifySecurityHash($this->secret, 'Order 12345');
    }

    /**
     * function test for verifySecurityHash valid
     *
     * @group integrationTest
     * @test
     */
    public function verifySecurityHashValid()
    {
        $response = new Response();
        $response->getProcessing()->set('result', 'ACK');
        $response->getCriterion()->set(
            'secret',
            '84c48ba8b3386a4e2f38ef5eeb3b3544788f675eef63c6e83c828049b706aa7e57ba69243902bfcd105'
            . 'c1ed28b6519fb4277b3355b9807819dd4b0414722a3f5'
        );
        $this->assertTrue(
            $response->verifySecurityHash(
                $this->secret,
                'InvoicePaymentMethodTest::Refund 2017-02-24 10:22:35'
            )
        );
    }

    /**
     * function test for verifySecurityHash invalid
     *
     * @group integrationTest
     * @test
     */
    public function verifySecurityHashInvalid()
    {
        $response = new Response();
        $response->getProcessing()->set('result', 'ACK');
        $this->expectException(HashVerificationException::class);
        $response->getCriterion()->set(
            'secret',
            '84c48ba8b3386a4e2f38ef5eeb3b3544788f675eef63c6e83c828049b706aa7e57ba69243902bfcd105'
            . 'c1ed28b6519fb4277b3355b9807819dd4b0414722a3f5'
        );
        $response->verifySecurityHash($this->secret, 'false');
    }

    /**
     * @test
     */
    public function jsonSerializeTest()
    {
        $this->assertNotEmpty($this->responseObject->jsonSerialize());
        $this->assertArrayHasKey('account', $this->responseObject->jsonSerialize());
        $this->assertArrayHasKey('address', $this->responseObject->jsonSerialize());
        $this->assertArrayHasKey('basket', $this->responseObject->jsonSerialize());
        $this->assertArrayHasKey('config', $this->responseObject->jsonSerialize());
        $this->assertArrayHasKey('connector', $this->responseObject->jsonSerialize());
        $this->assertArrayHasKey('contact', $this->responseObject->jsonSerialize());
        $this->assertArrayHasKey('criterion', $this->responseObject->jsonSerialize());
        $this->assertArrayHasKey('frontend', $this->responseObject->jsonSerialize());
        $this->assertArrayHasKey('identification', $this->responseObject->jsonSerialize());
        $this->assertArrayHasKey('name', $this->responseObject->jsonSerialize());
        $this->assertArrayHasKey('payment', $this->responseObject->jsonSerialize());
        $this->assertArrayHasKey('presentation', $this->responseObject->jsonSerialize());
        $this->assertArrayHasKey('processing', $this->responseObject->jsonSerialize());
        $this->assertArrayHasKey('request', $this->responseObject->jsonSerialize());
        $this->assertArrayHasKey('riskinformation', $this->responseObject->jsonSerialize());
        $this->assertArrayHasKey('security', $this->responseObject->jsonSerialize());
        $this->assertArrayHasKey('transaction', $this->responseObject->jsonSerialize());
        $this->assertArrayHasKey('user', $this->responseObject->jsonSerialize());
    }

    /**
     * Test to verify that toJson returns valid JSON.
     *
     * @test
     */
    public function toJsonTest()
    {
        $this->assertJson($this->responseObject->toJson());
    }
}
