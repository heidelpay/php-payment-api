<?php

namespace Heidelpay\Tests\PhpApi\Unit\PaymentMethods;

use Heidelpay\PhpApi\Adapter\CurlAdapter;
use Heidelpay\PhpApi\Request;
use PHPUnit\Framework\TestCase;
use Heidelpay\PhpApi\PaymentMethods\SofortPaymentMethod;
use Heidelpay\PhpApi\Exceptions\UndefinedTransactionModeException;

/**
 * Sofort Test
 *
 * Connection tests can fail due to network issues and scheduled down times.
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
class AbstractPaymentMethodTest extends TestCase
{
    /**
     * PaymentObject
     *
     * @var \Heidelpay\PhpApi\PaymentMethods\SofortPaymentMethod
     */
    protected $paymentObject;

    /**
     * Set up function will create a sofort object for each test case
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
        $Abstract = new SofortPaymentMethod();

        $this->paymentObject = $Abstract;
    }

    /**
     * Request setter/getter test
     *
     * @test
     */
    public function Request()
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
    public function Response()
    {
        $this->assertEquals(null, $this->paymentObject->getResponse());
    }

    /**
     * Adapter setter/getter test
     *
     * @test
     */
    public function Adapter()
    {
        $Adapter = new CurlAdapter();
        $this->paymentObject->setAdapter($Adapter);

        $this->assertEquals($Adapter, $this->paymentObject->getAdapter());
    }

    /**
     * getPaymentUrl test
     *
     * @test
     */
    public function getPaymentUrl()
    {
        $this->paymentObject->getRequest()->getTransaction()->set('mode', 'LIVE');
        $this->assertEquals('https://heidelpay.hpcgw.net/ngw/post', $this->paymentObject->getPaymentUrl());
    }

    /**
     * getPaymentUrl exception test
     *
     * @test
     */
    public function getPaymentUrlException()
    {
        $this->paymentObject->getRequest()->getTransaction()->set('mode', null);
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
        $this->assertNotEmpty($this->paymentObject->jsonSerialize());
        $this->assertArrayHasKey('_paymentCode', $this->paymentObject->jsonSerialize());
        $this->assertArrayHasKey('_brand', $this->paymentObject->jsonSerialize());
        $this->assertArrayHasKey('_liveUrl', $this->paymentObject->jsonSerialize());
        $this->assertArrayHasKey('_sandboxUrl', $this->paymentObject->jsonSerialize());
        $this->assertArrayHasKey('_adapter', $this->paymentObject->jsonSerialize());
        $this->assertArrayHasKey('_request', $this->paymentObject->jsonSerialize());
        $this->assertArrayHasKey('_requestArray', $this->paymentObject->jsonSerialize());
        $this->assertArrayHasKey('_response', $this->paymentObject->jsonSerialize());
        $this->assertArrayHasKey('_responseArray', $this->paymentObject->jsonSerialize());
        $this->assertArrayHasKey('_dryRun', $this->paymentObject->jsonSerialize());
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
}
