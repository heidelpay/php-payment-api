<?php

namespace Heidelpay\Tests\PhpApi\Unit\ParameterGroup;

use PHPUnit\Framework\TestCase;
use Heidelpay\PhpApi\ParameterGroups\AddressParameterGroup as Address;

/**
 * Unit test for AccountParameterGroup
 *
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
class AddressParameterGroupTest extends TestCase
{
    /**
     * City getter/setter test
     */
    public function testCity()
    {
        $address = new Address();

        $city = "Heidelberg";
        $address->setCity($city);

        $this->assertEquals($city, $address->getCity());
    }

    /**
     * Country getter/setter test
     */
    public function testCountry()
    {
        $address = new Address();

        $country = "DE";
        $address->setCountry($country);

        $this->assertEquals($country, $address->getCountry());
    }

    /**
     * State getter/setter test
     */
    public function testState()
    {
        $address = new Address();

        $state = "DE-BW";
        $address->setState($state);

        $this->assertEquals($state, $address->getState());
    }

    /**
     * Street getter/setter test
     */
    public function testStreet()
    {
        $address = new Address();

        $street = "Märchenweg 123";
        $address->setStreet($street);

        $this->assertEquals($street, $address->getStreet());
    }

    /**
     * Zip getter/setter test
     */
    public function testZip()
    {
        $address = new Address();

        $zip = "69115";
        $Address->setZip($zip);

        $this->assertEquals($zip, $address->getZip());
    }
}
