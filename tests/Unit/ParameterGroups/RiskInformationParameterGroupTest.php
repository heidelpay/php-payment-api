<?php

namespace Heidelpay\Tests\PhpApi\Unit\ParameterGroup;

use PHPUnit\Framework\TestCase;
use Heidelpay\PhpApi\ParameterGroups\RiskInformationParameterGroup as RiskInformation;

/**
 * Unit test for RiskInformationParameterGroup
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  https://dev.heidelpay.de/PhpApi
 *
 * @author  Daniel Kraut
 *
 * @category unittest
 */
class RiskinformationParameterGroupTest extends TestCase
{
    /**
     * Guestcheckout getter/setter test
     */
    public function testGuestcheckout()
    {
        $RiskInformation = new RiskInformation();

        $guestcheckout = false;
        $RiskInformation->set('guestcheckout', $guestcheckout);

        $this->assertFalse($RiskInformation->getGuestcheckout(), 'guestcheckout should be false');
    }

    /**
     * Since getter/setter test
     */
    public function testSince()
    {
        $RiskInformation = new RiskInformation();

        $since = "1984-05-23";
        $RiskInformation->set('since', $since);

        $this->assertEquals($since, $RiskInformation->getSince());
    }

    /**
     * Ordercount getter/setter test
     */
    public function testOrdercount()
    {
        $RiskInformation = new RiskInformation();

        $ordercount = 5;
        $RiskInformation->set('ordercount', $ordercount);

        $this->assertEquals($ordercount, $RiskInformation->getOrdercount());
    }
}
