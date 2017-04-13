<?php

namespace Heidelpay\Tests\PhpApi\Unit\ParameterGroup;

use PHPUnit\Framework\TestCase;
use Heidelpay\PhpApi\ParameterGroups\RequestParameterGroup as Request;

/**
 * Unit test for RequestParameterGroup
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
class RequestParameterGroupTest extends TestCase
{
    /**
     * Request version getter/setter test
     */
    public function testVersion()
    {
        $Request = new Request();

        $value = '1.2';
        $Request->set('version', $value);

        $this->assertEquals($value, $Request->getVersion());
    }
}
