<?php
namespace Heidelpay\Tests\PhpApi\Unit\ParameterGroup;

use PHPUnit\Framework\TestCase;
use Heidelpay\PhpApi\ParameterGroups\ConfigParameterGroup as Config;

/**
 * Unit test for ConfigParameterGroup
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
class ConfigParameterGroupTest extends TestCase
{
    /**
     * BankCountry getter/setter test
     */
    public function testBankCountry()
    {
        $Config = new Config();
        
        $value = array('NL' => 'Niederlande');
        $Config->set('bankcountry', json_encode($value));
        
        $this->assertEquals($value, $Config->getBankCountry());
    }
    
    /**
     * BankCountry getter/setter test
     */
    public function testBrands()
    {
        $Config = new Config();
        
        $value = array(
            'ING_TEST' => 'Test Bank',
            'INGBNL2A' => 'Issuer Simulation V3 - ING',
            'RABONL2U' => 'Issuer Simulation V3 - RABO'
        );
        
        $Config->set('brands', json_encode($value));
        
        $this->assertEquals($value, $Config->getBrands());
    }
}
