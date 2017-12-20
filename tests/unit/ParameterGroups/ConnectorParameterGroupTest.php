<?php

namespace Heidelpay\Tests\PhpPaymentApi\Unit\ParameterGroup;

use Codeception\TestCase\Test;
use Heidelpay\PhpPaymentApi\ParameterGroups\ConnectorParameterGroup as Connector;

/**
 * Unit test for ConnectorParameterGroup
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
class ConnectorParameterGroupTest extends Test
{
    /**
     * AccountBank getter/setter test
     *
     * @test
     */
    public function accountBank()
    {
        $connector = new Connector();

        $value = '37040044';
        $connector->set('account_bank', $value);

        /** @noinspection PhpDeprecationInspection */
        $this->assertEquals($value, $connector->getAccountBank());
    }

    /**
     * AccountBic getter/setter test
     *
     * @test
     */
    public function accountBic()
    {
        $connector = new Connector();

        $value = 'COBADEFFXXX';
        $connector->set('account_bic', $value);

        $this->assertEquals($value, $connector->getAccountBic());
    }

    /**
     * AccountCountry getter/setter test
     *
     * @test
     */
    public function accountCountry()
    {
        $connector = new Connector();

        $value = 'DE';
        $connector->set('account_country', $value);

        $this->assertEquals($value, $connector->getAccountCountry());
    }

    /**
     * AccountHolder getter/setter test
     *
     * @test
     */
    public function accountHolder()
    {
        $connector = new Connector();

        $value = 'Heidelberger Payment GmbH';
        $connector->set('account_holder', $value);

        $this->assertEquals($value, $connector->getAccountHolder());
    }

    /**
     * AccountIBan getter/setter test
     *
     * @test
     */
    public function accountIBan()
    {
        $connector = new Connector();

        $value = 'DE89370400440532013000';
        $connector->set('account_iban', $value);

        $this->assertEquals($value, $connector->getAccountIBan());
    }

    /**
     * AccountNumber getter/setter test
     *
     * @test
     */
    public function accountNumber()
    {
        $connector = new Connector();

        $value = '5320130';
        $connector->set('account_number', $value);

        /** @noinspection PhpDeprecationInspection */
        $this->assertEquals($value, $connector->getAccountNumber());
    }

    /**
     * account_usage getter/setter test
     *
     * @test
     */
    public function accountUsage()
    {
        $connector = new Connector();

        $value = '5320130';
        $connector->set('account_usage', $value);

        $this->assertEquals($value, $connector->getAccountUsage());
    }

    /**
     * Verify called class equals the connector class.
     *
     * @test
     */
    public function verifyCalledClassEqualsTheConnectorClass()
    {
        $connector = new Connector();
        $calledClass = $connector::getClassName();
        $this->assertEquals(get_class($connector), $calledClass);
    }
}
