<?php
namespace Heidelpay\Tests\PhpApi\Unit\ParameterGroup;

use PHPUnit\Framework\TestCase;
use Heidelpay\PhpApi\ParameterGroups\IdentificationParameterGroup as Identification;

/**
 * Unit test for IdentificationParameterGroup
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
class IdentificationParameterGroupTest extends TestCase
{
    /**
     * Creditor id getter/setter test
     */
    public function testCreditorId()
    {
        $Identification = new Identification();
    
        $value = '200123';
        $Identification->set('creditor_id', $value);
    
        $this->assertEquals($value, $Identification->getCreditorId());
    }
    
    /**
     * Shopper id getter/setter test
     */
    public function testShopperId()
    {
        $Identification = new Identification();
        
        $value = '200123';
        $Identification->set('shopperid', $value);
        
        $this->assertEquals($value, $Identification->getShopperId());
    }
    
    /**
     * Short id getter/setter test
     */
    public function testShortId()
    {
        $Identification = new Identification();
    
        $value = '200.500.210';
        $Identification->set('shortid', $value);
    
        $this->assertEquals($value, $Identification->getShortId());
    }

    /**
     * Transaction id getter/setter test
     */
    public function testTransactionId()
    {
        $Identification = new Identification();
        
        $value = '0860300156-ngw|php-conncetor';
        $Identification->set('transactionid', $value);
        
        $this->assertEquals($value, $Identification->getTransactionId());
    }

    /**
     * Reference id getter/setter test
     */
    public function testReferenceId()
    {
        $Identification = new Identification();
    
        $value = '31HA07BC8142C5A171745D00AD233923';
        $Identification->set('referenceid', $value);
    
        $this->assertEquals($value, $Identification->getReferenceId());
    }
    
    /**
     * Uniqe id getter/setter test
     */
    public function testUniqueId()
    {
        $Identification = new Identification();
    
        $value = '31HA07BC8142C5A171745D00AD233923';
        $Identification->set('uniqueid', $value);
    
        $this->assertEquals($value, $Identification->getUniqueId());
    }
}
