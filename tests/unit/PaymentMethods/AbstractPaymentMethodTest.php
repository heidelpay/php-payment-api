<?php

namespace Heidelpay\Tests\PhpPaymentApi\Unit\PaymentMethods;

use Heidelpay\PhpPaymentApi\Adapter\CurlAdapter;
use Heidelpay\PhpPaymentApi\Constants\ApiConfig;
use Heidelpay\PhpPaymentApi\Request;
use Heidelpay\PhpPaymentApi\PaymentMethods\SofortPaymentMethod;
use Heidelpay\PhpPaymentApi\Exceptions\UndefinedTransactionModeException;
use Heidelpay\PhpPaymentApi\Response;
use Heidelpay\Tests\PhpPaymentApi\Helper\BasePaymentMethodTest;

/**
 * This test class contains tests focusing on the base trait.
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author  Jens Richter
 *
 * @package heidelpay\php-payment-api\tests\unit
 */
class AbstractPaymentMethodTest extends BasePaymentMethodTest
{
    //<editor-fold desc="Init">

    /**
     * PaymentObject
     *
     * @var SofortPaymentMethod
     */
    protected $paymentObject;

    /**
     * Set up function will create a sofort object for each test case
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    // @codingStandardsIgnoreStart
    public function _before()
    {
        // @codingStandardsIgnoreEnd
        $Abstract = new SofortPaymentMethod();

        $this->paymentObject = $Abstract;
    }

    //</editor-fold>

    //<editor-fold desc="Tests">

    /**
     * Request setter/getter test
     *
     * @test
     */
    public function request()
    {
        $Request = new Request();
        $this->paymentObject->setRequest($Request);

        $this->assertEquals($Request, $this->paymentObject->getRequest());
    }

    /**
     * Response setter/getter test
     *
     * @test
     */
    public function response()
    {
        $this->assertEquals(null, $this->paymentObject->getResponse());
    }

    /**
     * Adapter setter/getter test
     *
     * @test
     */
    public function adapter()
    {
        $Adapter = new CurlAdapter();
        $this->paymentObject->setAdapter($Adapter);

        $this->assertSame($Adapter, $this->paymentObject->getAdapter());
    }

    /**
     * getPaymentUrl test
     *
     * @test
     *
     * @throws \Exception
     */
    public function getPaymentUrl()
    {
        $this->paymentObject->getRequest()->getTransaction()->setMode('LIVE');
        $this->assertSame(ApiConfig::LIVE_URL, $this->paymentObject->getPaymentUrl());
    }

    /**
     * getPaymentUrl exception test
     *
     * @test
     *
     * @throws \Exception
     */
    public function getPaymentUrlException()
    {
        $this->paymentObject->getRequest()->getTransaction()->setMode(null);
        $this->expectException(UndefinedTransactionModeException::class);
        $this->paymentObject->getPaymentUrl();
    }

    /**
     * Test if jsonSerialize returns elements.
     *
     * @test
     */
    public function jsonSerializeTest()
    {
        $objectAsJson = $this->paymentObject->jsonSerialize();
        $this->assertNotEmpty($objectAsJson);
        $this->assertArrayHasKey('paymentCode', $objectAsJson);
        $this->assertArrayHasKey('brand', $objectAsJson);
        $this->assertArrayHasKey('adapter', $objectAsJson);
        $this->assertArrayHasKey('request', $objectAsJson);
        $this->assertArrayHasKey('requestArray', $objectAsJson);
        $this->assertArrayHasKey('response', $objectAsJson);
        $this->assertArrayHasKey('responseArray', $objectAsJson);
        $this->assertArrayHasKey('dryRun', $objectAsJson);
    }

    /**
     * Test to verify that toJson returns valid JSON.
     *
     * @test
     */
    public function toJsonTest()
    {
        $this->assertJson($this->paymentObject->toJson());
    }

    /**
     * Test whether the config getter returns the same object in call 1 and call 2.
     *
     * @test
     */
    public function responseConfigGetterAlwaysReturnsTheSameObject()
    {
        $response = new Response();
        $config = $response->getConfig();
        $this->assertSame($config, $response->getConfig());
    }

    /**
     * Test whether the config getter returns the same object in call 1 and call 2.
     *
     * @test
     */
    public function responseRiskInformationGetterAlwaysReturnsTheSameObject()
    {
        $response = new Response();
        $config = $response->getRiskInformation();
        $this->assertSame($config, $response->getRiskInformation());
    }

    //</editor-fold>
}
