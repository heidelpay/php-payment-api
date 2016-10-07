<?php
namespace Heidelpay\Tests\PhpApi\Unit\ParameterGroup;
use PHPUnit\Framework\TestCase;
use \Heidelpay\PhpApi\ParameterGroups\CriterionParameterGroup as Criterion;
/**
 * Unit test for CriterionParameterGroup
 *
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 * @link  https://dev.heidelpay.de/PhpApi
 * @author  Jens Richter
 * @category unittest
 */
class CriterionParameterGroupTest extends TestCase {
    
    /**
     * Security setter/getter test
     */
    
    public function testSecret(){
        
        $Criterion = new Criterion();
        
        $value = "OrderId 12483423894";
        $secretHash = "235894234023049afasrfew2";
        
        $Criterion->setSecret($value, $secretHash);
        
        $result = hash('sha512',$value.$secretHash);
        
        $this->assertEquals($result, $Criterion->getSecretHash());
    }
    
}