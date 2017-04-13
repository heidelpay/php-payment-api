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
        $Address = new Address();

        $city = "Heidelberg";
        $Address->set('city', $city);

        $this->assertEquals($city, $Address->getCity());
    }

    /**
     * Country getter/setter test
     */
    public function testCountry()
    {
        $Address = new Address();

        $country = "DE";
        $Address->set('country', $country);

        $this->assertEquals($country, $Address->getCountry());
    }

    /**
     * State getter/setter test
     */
    public function testState()
    {
        $Address = new Address();

        $state = "DE-BW";
        $Address->set('state', $state);

        $this->assertEquals($state, $Address->getState());
    }

    /**
     * Street getter/setter test
     */
    public function testStreet()
    {
        $Address = new Address();

        $street = "Märchenweg 123";
        $Address->set('street', $street);

        $this->assertEquals($street, $Address->getStreet());
    }

    /**
     * Zip getter/setter test
     */
    public function testZip()
    {
        $Address = new Address();

        $zip = "69115";
        $Address->set('zip', $zip);

        $this->assertEquals($zip, $Address->getZip());
    }
}
