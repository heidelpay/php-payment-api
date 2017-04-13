<?php

namespace Heidelpay\Tests\PhpApi\Unit\ParameterGroup;

use PHPUnit\Framework\TestCase;
use Heidelpay\PhpApi\ParameterGroups\PresentationParameterGroup as Presentation;

/**
 * Unit test for PresentationParameterGroup
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
class PresentationParameterGroupTest extends TestCase
{
    /**
     * Amount getter/setter test
     */
    public function testAmount()
    {
        $Presentation = new Presentation();

        $value = '20.11';
        $Presentation->set('amount', $value);

        $this->assertEquals($value, $Presentation->getAmount());
    }

    /**
     * Currency getter/setter test
     */
    public function testCurrency()
    {
        $Presentation = new Presentation();

        $value = 'USD';
        $Presentation->set('currency', $value);

        $this->assertEquals($value, $Presentation->getCurrency());
    }

    /**
     * Usage getter/setter test
     *
     * @test
     */
    public function PresentationUsage()
    {
        $Presentation = new Presentation();

        $value = 'Heidelpay Invoice ID 12345';
        $Presentation->set('usage', $value);

        $this->assertEquals($value, $Presentation->getUsage());
    }
}
