<?php

namespace Heidelpay\Tests\PhpPaymentApi\Unit\ParameterGroup;

use Codeception\TestCase\Test;
use Heidelpay\PhpPaymentApi\ParameterGroups\TransactionParameterGroup as Transaction;

/**
 * Unit test for TransactionParameterGroup
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author  Jens Richter
 *
 * @category unittest
 */
class TransactionParameterGroupTest extends Test
{
    /**
     * Channel getter/setter test
     *
     * @test
     */
    public function channel()
    {
        $transaction = new Transaction();

        $value = '31HA07BC8142C5A171749A60D979B6E4';
        $transaction->setChannel($value);

        $this->assertEquals($value, $transaction->getChannel());
    }

    /**
     * Mode getter/setter test
     *
     * @test
     */
    public function mode()
    {
        $transaction = new Transaction();

        $value = 'LIVE';
        $transaction->setMode($value);

        $this->assertEquals($value, $transaction->getMode());
    }

    /**
     * Test for response getter/setter.
     *
     * @test
     */
    public function responseTest()
    {
        $transaction = new Transaction();

        $value = 'SYNC';
        $transaction->setResponse($value);

        $this->assertEquals($value, $transaction->getResponse());
    }
}
