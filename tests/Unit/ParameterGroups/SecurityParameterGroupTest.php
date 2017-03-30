<?php

namespace Heidelpay\Tests\PhpApi\Unit\ParameterGroup;

use PHPUnit\Framework\TestCase;
use Heidelpay\PhpApi\ParameterGroups\SecurityParameterGroup as Security;

/**
 * Unit test for SecurityParameterGroup
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
class SecurityParameterGroupTest extends TestCase
{
    /**
     * Sender getter/setter test
     */
    public function testSender()
    {
        $Security = new Security();

        $value = '31HA07BC8142C5A171745D00AD63D182';
        $Security->set('sender', $value);

        $this->assertEquals($value, $Security->getSender());
    }
}
