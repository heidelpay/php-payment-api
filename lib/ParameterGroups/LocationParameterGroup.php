<?php

namespace Heidelpay\PhpPaymentApi\ParameterGroups;

/**
 * This class provides every api parameter related to the company location data
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
class LocationParameterGroup extends AbstractParameterGroup
{
    /**
     * Company location pobox
     *
     * @var string
     */
    public $pobox;

    /**
     * Company location street
     *
     * @var string
     */
    public $street;

    /**
     * Company location zip
     *
     * @var string
     */
    public $zip;

    /**
     * Company location city
     *
     * @var string
     */
    public $city;

    /**
     * Company location country
     *
     * @var string
     */
    public $country;

    /**
     * Company location pobox getter
     *
     * @return string
     */
    public function getPobox()
    {
        return $this->pobox;
    }

    /**
     * Setter for company location pobox
     *
     * @param $pobox
     *
     * @return $this
     */
    public function setPobox($pobox)
    {
        $this->pobox = $pobox;
        return $this;
    }

    /**
     * Company location street getter
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Setter for company location street
     *
     * @param $street
     *
     * @return $this
     */
    public function setStreet($street)
    {
        $this->street = $street;
        return $this;
    }

    /**
     * Company location zip getter
     *
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Setter for company location pobox
     *
     * @param $zip
     *
     * @return $this
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
        return $this;
    }

    /**
     * Company location city getter
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Setter for company location city
     *
     * @param $city
     *
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * Company location country getter
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Setter for company location country
     *
     * @param $country
     *
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }
}
