<?php

namespace Heidelpay\Tests\PhpPaymentApi\Unit\ParameterGroup;

use Codeception\TestCase\Test;
use Heidelpay\PhpPaymentApi\ParameterGroups\IdentificationParameterGroup as Identification;

/**
 * Unit test for IdentificationParameterGroup
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
class IdentificationParameterGroupTest extends Test
{
    /**
     * Creditor id getter/setter test
     *
     * @test
     */
    public function creditorId()
    {
        $identification = new Identification();

        $value = '200123';
        $identification->set('creditor_id', $value);

        $this->assertEquals($value, $identification->getCreditorId());
    }

    /**
     * Shopper id getter/setter test
     *
     * @test
     */
    public function shopperId()
    {
        $identification = new Identification();

        $value = '200123';
        $identification->setShopperid($value);

        $this->assertEquals($value, $identification->getShopperId());
    }

    /**
     * Short id getter/setter test
     *
     * @test
     */
    public function shortId()
    {
        $identification = new Identification();

        $value = '200.500.210';
        $identification->set('shortid', $value);

        $this->assertEquals($value, $identification->getShortId());
    }

    /**
     * Transaction id getter/setter test
     *
     * @test
     */
    public function transactionId()
    {
        $identification = new Identification();

        $value = '0860300156-ngw|php-connector';
        $identification->setTransactionid($value);

        $this->assertEquals($value, $identification->getTransactionId());
    }

    /**
     * Reference id getter/setter test
     *
     * @test
     */
    public function referenceId()
    {
        $identification = new Identification();

        $value = '31HA07BC8142C5A171745D00AD233923';
        $identification->setReferenceid($value);

        $this->assertEquals($value, $identification->getReferenceId());
    }

    /**
     * Unique id getter/setter test
     *
     * @test
     */
    public function uniqueId()
    {
        $identification = new Identification();

        $value = '31HA07BC8142C5A171745D00AD233923';
        $identification->set('uniqueid', $value);

        $this->assertEquals($value, $identification->getUniqueId());
    }
}
