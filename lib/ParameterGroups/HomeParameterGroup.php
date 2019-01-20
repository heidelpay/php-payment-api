<?php

namespace Heidelpay\PhpPaymentApi\ParameterGroups;

/**
 * This class provides every api parameter related to the executive home data
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-payment-api/
 *
 * @author  David Owusu
 *
 * @package heidelpay\php-payment-api\parameter-groups
 */
class HomeParameterGroup extends AbstractParameterGroup
{
    /**
     * Executive home street
     *
     * @var string
     */
    public $street;

    /**
     * Executive home zip
     *
     * @var string
     */
    public $zip;

    /**
     * Executive home city
     *
     * @var string
     */
    public $city;

    /**
     * Executive home country
     *
     * @var string
     */
    public $country;

    public function __construct(
        $street = null,
        $zip = null,
        $city = null,
        $country = null
    ) {
        $this->street = $street;
        $this->zip = $zip;
        $this->city = $city;
        $this->country = $country;
    }

    /**
     * Executive home street getter
     *
     * @return string | null
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Setter for executive home street
     *
     * @param string $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
        return $this;
    }

    /**
     * Executive home zip getter
     *
     * @return string | null
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Setter for executive home zip
     *
     * @param string $zip
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
        return $this;
    }

    /**
     * Executive home city getter
     *
     * @return string | null
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Setter for executive home city
     *
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * Executive home country getter
     *
     * @return string | null
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Setter for executive home country
     *
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }
}
