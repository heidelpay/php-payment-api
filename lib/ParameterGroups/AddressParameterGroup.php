<?php

namespace Heidelpay\PhpPaymentApi\ParameterGroups;

/**
 * This class provides every api parameter related to the customers billingaddress data
 *
 * Depending on the used payment method billing and shipping address should
 * be equal.
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-payment-api/
 *
 * @author  Jens Richter
 *
 * @package heidelpay\php-payment-api\parameter-groups
 */
class AddressParameterGroup extends AbstractParameterGroup
{
    /**
     * @var string city of the customers billingaddress (mandatory)
     */
    public $city;

    /**
     * @var string county of the customers billingaddress in ISO 3166-1 2 digits (mandatory)
     */
    public $country;

    /**
     * @var string state of the customers billingaddress in ISO 3166-2 (optinal)
     */
    public $state;

    /**
     * @var string street of the customers billingaddress (mandatory)
     */
    public $street;

    /**
     * @var string zip code of the customers billingaddress (mandatory)
     */
    public $zip;

    /**
     * AddressCity getter
     *
     * @return string city
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * AddressCountry getter
     *
     * @return string country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * AddressState getter
     *
     * @return string state
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * AddressStreet getter
     *
     * @return string street
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * AddressZip getter
     *
     * @return string zip
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Setter for the customer city
     *
     * @param string $city address city
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\AddressParameterGroup
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * Setter for the address country code in 2 letters
     *
     * @param string $country iso country code
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\AddressParameterGroup
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Setter for the iso state code
     *
     * @param $state string  iso address state code
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\AddressParameterGroup
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * Setter for the address street including house number
     *
     * @param string $street address street including house number
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\AddressParameterGroup
     */
    public function setStreet($street)
    {
        $this->street = $street;
        return $this;
    }

    /**
     * Setter for the address zip
     *
     * @param string $zip zip code
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\AddressParameterGroup
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
        return $this;
    }
}
