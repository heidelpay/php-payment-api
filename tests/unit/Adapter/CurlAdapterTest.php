<?php

namespace Heidelpay\Tests\PhpPaymentApi\Unit\Adapter;

use Codeception\Test\Unit;
use Heidelpay\PhpPaymentApi\Constants\PaymentMethod;
use Heidelpay\PhpPaymentApi\Constants\ProcessingResult;
use Heidelpay\PhpPaymentApi\Constants\TransactionType;
use Heidelpay\PhpPaymentApi\Response;
use Heidelpay\PhpPaymentApi\Adapter\CurlAdapter;
use AspectMock\Test as test;
use Heidelpay\Tests\PhpPaymentApi\Helper\Constraints\ArraysMatchConstraint;
use PHPUnit\Framework\Constraint\Constraint;
use Heidelpay\PhpPaymentApi\Constants\TransactionMode;

/**
 * Unit test for the curl adapter
 *
 * This unit test will cover an error in the connection and an simple post
 * request to the sandbox payment system. Please note stat connection
 * test can false due to network issues and scheduled down times.
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-payment-api/
 *
 * @author  Jens Richter
 *
 * @package heidelpay\php-payment-api\tests\unit
 */
class CurlAdapterTest extends Unit
{
    /** @var  CurlAdapter $curlAdapter */
    private $curlAdapter;

    /**
     * @Override
     */
    // @codingStandardsIgnoreStart
    public function _before()
    {
        // @codingStandardsIgnoreEnd
        $this->curlAdapter = new CurlAdapter();

        parent::_before();
    }

    /**
     * @Override
     */
    // @codingStandardsIgnoreStart
    public function _after()
    {
        // @codingStandardsIgnoreEnd
        $this->curlAdapter = null;

        test::clean();
        parent::_after();
    }

    /**
     * Verify that an error result is returned if curl extension is not loaded.
     *
     * @test
     */
    public function sendPostShouldReturnNokResultIfCurlIsNotLoaded()
    {
        test::func(
            'Heidelpay\PhpPaymentApi\Adapter',
            'extension_loaded',
            false
        );

        list($response_array, ) = $this->curlAdapter->sendPost('', '');

        $expected = [
            'PROCESSING_RESULT' => ProcessingResult::NOK,
            'PROCESSING_RETURN' => 'Connection error php-curl not installed',
            'PROCESSING_RETURN_CODE' => 'CON.ERR.CUR'
        ];

        /** @var Constraint $arraysMatchConstraint */
        $arraysMatchConstraint = new ArraysMatchConstraint($response_array, true, true);
        $this->assertThat($expected, $arraysMatchConstraint);
    }

    /**
     * Verify that an error result is returned if curl extension is not loaded.
     *
     * @test
     */
    public function sendPostShouldReturnErrorCodeIfCurlInfoHttpCodeIsSet()
    {
        test::func(
            'Heidelpay\PhpPaymentApi\Adapter',
            'curl_getinfo',
            ['CURLINFO_HTTP_CODE' => 'MY_TEST_ERROR_CODE']
        );
        test::func(
            'Heidelpay\PhpPaymentApi\Adapter',
            'curl_error',
            'Test Error'
        );

        list($response_array, ) = $this->curlAdapter->sendPost('', '');

        $expected = [
            'PROCESSING_RESULT' => ProcessingResult::NOK,
            'PROCESSING_RETURN' => 'Connection error http status Test Error' ,
            'PROCESSING_RETURN_CODE' => 'CON.ERR.MY_TEST_ERROR_CODE'
        ];

        /** @var Constraint $arraysMatchConstraint */
        $arraysMatchConstraint = new ArraysMatchConstraint($response_array, true, true);
        $this->assertThat($expected, $arraysMatchConstraint);
    }

    /**
     * This test will cover the error handling of the curl adapter
     *
     * @group connectionTest
     * @test
     */
    public function hostNotFound()
    {
        /** @var array $result_array */
        /** @var Response $response */
        list($result_array, $response) = $this->curlAdapter->sendPost('https://abc.heidelpay.com/');

        $this->assertTrue(is_array($result_array), 'First result key should be an array.');
        $this->assertTrue(is_object($response), 'Second result key should be an object.');
        $expected = array(
            'PROCESSING_RESULT' => ProcessingResult::NOK,
            'PROCESSING_RETURN_CODE' => 'CON.ERR.DEF'
        );
        $this->assertEquals($expected['PROCESSING_RESULT'], $result_array['PROCESSING_RESULT']);
        $this->assertEquals($expected['PROCESSING_RETURN_CODE'], $result_array['PROCESSING_RETURN_CODE']);

        $this->assertTrue($response->isError(), 'isError should return true');
        $this->assertFalse($response->isSuccess(), 'isSuccess should return false');

        $error = $response->getError();

        $this->assertEquals($expected['PROCESSING_RETURN_CODE'], $error['code']);
    }

    /**
     * This test will send a simple request,
     *
     * @depends hostNotFound
     * @group connectionTest
     * @test
     */
    public function simplePost()
    {
        $post = array(
            'SECURITY.SENDER' => '31HA07BC8142C5A171745D00AD63D182',
            'USER.LOGIN' => '31ha07bc8142c5a171744e5aef11ffd3',
            'USER.PWD' => '93167DE7',
            'TRANSACTION.MODE' => TransactionMode::CONNECTOR_TEST,
            'TRANSACTION.CHANNEL' => '31HA07BC8142C5A171744F3D6D155865',
            'PAYMENT.CODE' => PaymentMethod::CREDIT_CARD . '.' . TransactionType::REGISTRATION,
            'FRONTEND.MODE' => 'WHITELABEL',
            'FRONTEND.ENABLED' => 'TRUE',
            'FRONTEND.LANGUAGE' => 'EN',
            'FRONTEND.RESPONSE_URL' => 'http://dev.heidelpay.com',
            'CONTACT.IP' => '127.0.0.1',
            'REQUEST.VERSION' => '1.0'
        );

        /** @var array $result_array */
        /** @var Response $response */
        list($result_array, $response) =
            $this->curlAdapter->sendPost('https://test-heidelpay.hpcgw.net/ngw/post', $post);

        $this->assertTrue(is_array($result_array), 'First result key should be an array.');
        $this->assertTrue(is_object($response), 'Second result key should be an object.');

        $this->assertFalse($response->isError(), 'isError should return false');
        $this->assertTrue($response->isSuccess(), 'isSuccess should return true');
    }
}
