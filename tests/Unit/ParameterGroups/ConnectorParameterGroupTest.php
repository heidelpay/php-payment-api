<?php

namespace Heidelpay\Tests\PhpApi\Unit\ParameterGroup;

use PHPUnit\Framework\TestCase;
use Heidelpay\PhpApi\ParameterGroups\ConnectorParameterGroup as Connector;

/**
 * Unit test for ConnectorParameterGroup
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
class ConnectorParameterGroupTest extends TestCase
{
    /**
     * AccountBank getter/setter test
     *
     * @test
     */
    public function AccountBank()
    {
        $Connector = new Connector();

        $value = '37040044';
        $Connector->set('account_bank', $value);

        $this->assertEquals($value, $Connector->getAccountBank());
    }

    /**
     * AccountBic getter/setter test
     *
     * @test
     */
    public function AccountBic()
    {
        $Connector = new Connector();

        $value = 'COBADEFFXXX';
        $Connector->set('account_bic', $value);

        $this->assertEquals($value, $Connector->getAccountBic());
    }

    /**
     * AccountCountry getter/setter test
     *
     * @test
     */
    public function AccountCountry()
    {
        $Connector = new Connector();

        $value = 'DE';
        $Connector->set('account_country', $value);

        $this->assertEquals($value, $Connector->getAccountCountry());
    }

    /**
     * AccountHolder getter/setter test
     *
     * @test
     */
    public function AccountHolder()
    {
        $Connector = new Connector();

        $value = 'Heidelberger Payment GmbH';
        $Connector->set('account_holder', $value);

        $this->assertEquals($value, $Connector->getAccountHolder());
    }

    /**
     * AccountIBan getter/setter test
     *
     * @test
     */
    public function AccountIBan()
    {
        $Connector = new Connector();

        $value = 'DE89370400440532013000';
        $Connector->set('account_iban', $value);

        $this->assertEquals($value, $Connector->getAccountIBan());
    }

    /**
     * AccountNumber getter/setter test
     *
     * @test
     */
    public function AccountNumber()
    {
        $Connector = new Connector();

        $value = '5320130';
        $Connector->set('account_number', $value);

        $this->assertEquals($value, $Connector->getAccountNumber());
    }
}
