<?php

namespace Heidelpay\Tests\PhpPaymentApi\Unit\ParameterGroup;

use Codeception\TestCase\Test;
use Heidelpay\PhpPaymentApi\ParameterGroups\AddressParameterGroup as Address;

/**
 * Unit test for AccountParameterGroup
 *
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
class AddressParameterGroupTest extends Test
{
    /**
     * City getter/setter test
     */
    public function testCity()
    {
        $address = new Address();

        $city = 'Heidelberg';
        $address->setCity($city);

        $this->assertEquals($city, $address->getCity());
    }

    /**
     * Country getter/setter test
     */
    public function testCountry()
    {
        $address = new Address();

        $country = 'DE';
        $address->setCountry($country);

        $this->assertEquals($country, $address->getCountry());
    }

    /**
     * State getter/setter test
     */
    public function testState()
    {
        $address = new Address();

        $state = 'DE-BW';
        $address->setState($state);

        $this->assertEquals($state, $address->getState());
    }

    /**
     * Street getter/setter test
     */
    public function testStreet()
    {
        $address = new Address();

        $street = 'Märchenweg 123';
        $address->setStreet($street);

        $this->assertEquals($street, $address->getStreet());
    }

    /**
     * Zip getter/setter test
     */
    public function testZip()
    {
        $address = new Address();

        $zip = '69115';
        $address->setZip($zip);

        $this->assertEquals($zip, $address->getZip());
    }
}
