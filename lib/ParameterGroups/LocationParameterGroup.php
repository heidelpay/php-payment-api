<?php
/**
 * Created by PhpStorm.
 * User: David.Owusu
 * Date: 22.11.2018
 * Time: 18:44
 */
namespace Heidelpay\PhpPaymentApi\ParameterGroups;

class LocationParameterGroup extends AbstractParameterGroup
{
    public $pobox;

    public $street;

    public $zip;

    public $city;

    public $country;

    /**
     * @return mixed
     */
    public function getPobox()
    {
        return $this->pobox;
    }

    /**
     * @param mixed $pobox
     */
    public function setPobox($pobox)
    {
        $this->pobox = $pobox;
    }

    /**
     * @return mixed
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param mixed $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * @return mixed
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @param mixed $zip
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }
}
