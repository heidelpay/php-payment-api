<?php
namespace Heidelpay\Tests\PhpApi\Unit\ParameterGroup;

use PHPUnit\Framework\TestCase;
use Heidelpay\PhpApi\ParameterGroups\FrontendParameterGroup as Frontend;

/**
 * Unit test for FrontendParameterGroup
 *
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
        $Frontend = new Frontend();
        
        $value = 'false';
        $Frontend->set('enabled', $value);
        
        $this->assertEquals($value, $Frontend->getEnabled());
    }

    /**
     * Language getter/setter test
     */
    public function testLanguage()
    {
        $Frontend = new Frontend();
    
        $value = 'EN';
        $Frontend->set('language', $value);
    
        $this->assertEquals($value, $Frontend->getLanguage());
    }

    /**
     * Redirect url getter/setter test
     */
    public function testRedirectUrl()
    {
        $Frontend = new Frontend();
    
        $value = 'https://dev.heidelpay.de';
        $Frontend->set('redirect_url', $value);
    
        $this->assertEquals($value, $Frontend->getRedirectUrl());
    }
    
    /**
     * Response url getter/setter test
     */
    public function testResponseUrl()
    {
        $Frontend = new Frontend();
    
        $value = 'https://dev.heidelpay.de';
        $Frontend->set('response_url', $value);
    
        $this->assertEquals($value, $Frontend->getResponseUrl());
    }
    
    /**
     * Payment frame origin getter/setter test
     */
    public function testPaymentFrameOrigin()
    {
        $Frontend = new Frontend();
        
        $value = 'https://dev.heidelpay.de';
        $Frontend->set('payment_frame_origin', $value);
        
        $this->assertEquals($value, $Frontend->getPaymentFrameOrigin());
    }

    /**
     * Payment frame url getter/setter test
     */
    public function testPaymentFrameUrl()
    {
        $Frontend = new Frontend();
    
        $value = 'https://dev.heidelpay.de';
        $Frontend->set('payment_frame_url', $value);
    
        $this->assertEquals($value, $Frontend->getPaymentFrameUrl());
    }
    
    /**
     * Payment css path getter/setter test
     */
    public function testPaymentCssPath()
    {
        $Frontend = new Frontend();
    
        $value = 'https://dev.heidelpay.de';
        $Frontend->set('css_path', $value);
    
        $this->assertEquals($value, $Frontend->getCssPath());
    }

    /**
     * Prevent async redirect getter/setter test
     */
    public function testPreventAsyncRedirect()
    {
        $Frontend = new Frontend();
    
        $value = 'TRUE';
        $Frontend->set('prevent_async_redirect', $value);
    
        $this->assertEquals($value, $Frontend->getPreventAsyncRedirect());
    }
}
