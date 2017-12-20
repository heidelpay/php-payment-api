<?php

namespace Heidelpay\Tests\PhpPaymentApi\Unit\ParameterGroup;

use Codeception\TestCase\Test;
use Heidelpay\PhpPaymentApi\ParameterGroups\BasketParameterGroup as Basket;

/**
 * Unit test for BasketParameterGroup
 *
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
class BasketParameterGroupTest extends Test
{
    /**
     * BasketId getter/setter test
     *
     * @test
     */
    public function basketId()
    {
        $Basket = new Basket();

        $value = '31HA07BC8129FBB819367B2205CD6FB4';
        $Basket->setId($value);

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

        $value = Basket::class;
        $this->assertEquals($value, get_class($Basket));
    }

    /**
     * Undefined property exception test
     *
    public function undefinedPropertyException()
    {
        $this->expectException(UndefinedPropertyException::class);

        $response = new Request();
        $response->getBasket()->set('test', 'test');
    }
     */
}
