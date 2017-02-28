<?php
namespace Heidelpay\Tests\PhpApi\Unit\ParameterGroup;

use PHPUnit\Framework\TestCase;
use Heidelpay\PhpApi\ParameterGroups\BasketParameterGroup as Basket;
use Heidelpay\PhpApi\Exceptions\UndefinedPropertyException;

/**
 * Unit test for BasketParameterGroup
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
class BasketParameterGroupTest extends TestCase
{
    /**
     * BasketId getter/setter test
     *
     * @test
     */
    public function BasketId()
    {
        $Basket = new Basket();

        $value = "31HA07BC8129FBB819367B2205CD6FB4";
        $Basket->set('id', $value);
        
        $this->assertEquals($value, $Basket->getId());
    }

    /**
     * GetClassName test
     *
     * @test
     */
    public function getClassName()
    {
        $Basket = new Basket();

        $value = "Heidelpay\PhpApi\ParameterGroups\BasketParameterGroup";
        $this->assertEquals($value, $Basket->getClassName());
    }

    /**
     * Undefined property exception test
     *
     * @test
     */
    public function undefinedPropertyExcetion()
    {
        $this->expectException(UndefinedPropertyException::class);
        $Basket = new Basket();
        $Basket->set('test', 'test');
    }
}
