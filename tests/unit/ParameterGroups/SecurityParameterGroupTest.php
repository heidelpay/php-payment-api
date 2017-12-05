<?php

namespace Heidelpay\Tests\PhpPaymentApi\Unit\ParameterGroup;

use Codeception\TestCase\Test;
use Heidelpay\PhpPaymentApi\ParameterGroups\SecurityParameterGroup as Security;

/**
 * Unit test for SecurityParameterGroup
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
class SecurityParameterGroupTest extends Test
{
    /**
     * Sender getter/setter test
     */
    public function testSender()
    {
        $Security = new Security();

        $value = '31HA07BC8142C5A171745D00AD63D182';
        $Security->setSender($value);

        $this->assertEquals($value, $Security->getSender());
    }
}
