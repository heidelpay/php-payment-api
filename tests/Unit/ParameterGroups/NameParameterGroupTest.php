<?php
namespace Heidelpay\Tests\PhpApi\Unit\ParameterGroup;
use PHPUnit\Framework\TestCase;
use \Heidelpay\PhpApi\ParameterGroups\NameParameterGroup as Name;
/**
 * Unit test for NameParameterGroup
 *
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 * @link  https://dev.heidelpay.de/PhpApi
 * @author  Jens Richter
 * @category unittest
 */
class NameParameterGroupTest extends TestCase
{
    /**
     * Company getter/setter test
     */
    public function testCompany()
    {
        $Name = new Name();
        
        $value = 'Heidelpay';
        $Name->set('company', $value);
        
        $this->assertEquals($value, $Name->getCompany());
    }
    /**
     * Given name getter/setter test
     */
    public function testGiven()
    {
        $Name = new Name();
        
        $value = 'Heidel';
        $Name->set('given',$value);
        
        $this->assertEquals($value, $Name->getGiven());
    }
    /**
     * Family name getter/setter test
     */
    public function testFamily()
    {
        $Name = new Name();
    
        $value = 'Berger-Payment';
        $Name->set('family',$value);
    
        $this->assertEquals($value, $Name->getFamily());
    }
}