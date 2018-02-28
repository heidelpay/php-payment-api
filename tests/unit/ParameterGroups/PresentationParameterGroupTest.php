<?php

namespace Heidelpay\Tests\PhpPaymentApi\Unit\ParameterGroup;

use Codeception\TestCase\Test;
use Heidelpay\PhpPaymentApi\ParameterGroups\PresentationParameterGroup as Presentation;

/**
 * Unit test for PresentationParameterGroup
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author  Jens Richter
 *
 * @package heidelpay\php-payment-api\tests\unit
 */
class PresentationParameterGroupTest extends Test
{
    /**
     * Amount getter/setter test
     *
     * @test
     */
    public function amount()
    {
        $presentation = new Presentation();

        $value = '20.11';
        $presentation->setAmount($value);

        $this->assertEquals($value, $presentation->getAmount());
    }

    /**
     * Currency getter/setter test
     *
     * @test
     */
    public function currency()
    {
        $presentation = new Presentation();

        $value = 'USD';
        $presentation->setCurrency($value);

        $this->assertEquals($value, $presentation->getCurrency());
    }

    /**
     * Usage getter/setter test
     *
     * @test
     */
    public function presentationUsage()
    {
        $presentation = new Presentation();

        $value = 'Heidelpay Invoice ID 12345';
        $presentation->setUsage($value);

        $this->assertEquals($value, $presentation->getUsage());
    }
}
