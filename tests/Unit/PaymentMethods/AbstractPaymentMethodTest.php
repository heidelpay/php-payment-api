<?php

namespace Heidelpay\Tests\PhpApi\Unit\PaymentMethods;

use PHPUnit\Framework\TestCase;
use Heidelpay\PhpApi\PaymentMethods\SofortPaymentMethod;
use Heidelpay\PhpApi\Exceptions\UndefinedTransactionModeException;

/**
 *
 *  Sofort Test
 *
 *  Connection tests can fail due to network issues and scheduled downtimes.
 *  This does not have to mean that your integration is broken. Please verify the given debug information
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  https://dev.heidelpay.de/PhpApi
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
    protected $paymentObject = null;

    /**
     * Set up function will create a sofort object for each testcase
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
        $Request = new \Heidelpay\PhpApi\Request;
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
     * Adaptere setter/getter test
     *
     * @test
     */
    public function Adapter()
    {
        $Adapter = new \Heidelpay\PhpApi\Adapter\CurlAdapter();
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
}
