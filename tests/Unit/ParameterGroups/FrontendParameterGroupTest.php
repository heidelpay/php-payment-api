<?php

namespace Heidelpay\Tests\PhpApi\Unit\ParameterGroup;

use PHPUnit\Framework\TestCase;
use Heidelpay\PhpApi\ParameterGroups\FrontendParameterGroup as Frontend;

/**
 * Unit test for FrontendParameterGroup
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  https://dev.heidelpay.de/PhpApi
 *
 * @author  Jens Richter
 *
 * @category unittest
 */
class FrontendParameterGroupTest extends TestCase
{
    /**
     * Enabled getter/setter test
     */
    public function testEnabled()
    {
        $frontend = new Frontend();

        $value = 'false';
        $frontend->setEnabled($value);

        $this->assertEquals($value, $frontend->getEnabled());
    }

    /**
     * Language getter/setter test
     */
    public function testLanguage()
    {
        $frontend = new Frontend();

        $value = 'EN';
        $frontend->setLanguage($value);

        $this->assertEquals($value, $frontend->getLanguage());
    }

    /**
     * Redirect url getter/setter test
     */
    public function testRedirectUrl()
    {
        $frontend = new Frontend();

        $value = 'https://dev.heidelpay.de';
        $frontend->set('redirect_url', $value);

        $this->assertEquals($value, $frontend->getRedirectUrl());
    }

    /**
     * Response url getter/setter test
     */
    public function testResponseUrl()
    {
        $frontend = new Frontend();

        $value = 'https://dev.heidelpay.de';
        $frontend->setResponseUrl($value);

        $this->assertEquals($value, $frontend->getResponseUrl());
    }

    /**
     * Payment frame origin getter/setter test
     */
    public function testPaymentFrameOrigin()
    {
        $frontend = new Frontend();

        $value = 'https://dev.heidelpay.de';
        $frontend->setPaymentFrameOrigin($value);

        $this->assertEquals($value, $frontend->getPaymentFrameOrigin());
    }

    /**
     * Payment frame url getter/setter test
     */
    public function testPaymentFrameUrl()
    {
        $frontend = new Frontend();

        $value = 'https://dev.heidelpay.de';
        $frontend->set('payment_frame_url', $value);

        $this->assertEquals($value, $frontend->getPaymentFrameUrl());
    }

    /**
     * Payment css path getter/setter test
     */
    public function testPaymentCssPath()
    {
        $frontend = new Frontend();

        $value = 'https://dev.heidelpay.de';
        $frontend->setCssPath($value);

        $this->assertEquals($value, $frontend->getCssPath());
    }

    /**
     * Prevent async redirect getter/setter test
     */
    public function testPreventAsyncRedirect()
    {
        $frontend = new Frontend();

        $value = 'TRUE';
        $frontend->setPreventAsyncRedirect($value);

        $this->assertEquals($value, $frontend->getPreventAsyncRedirect());
    }
}
