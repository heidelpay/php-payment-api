<?php
/**
 * Created by PhpStorm.
 * User: David.Owusu
 * Date: 03.01.2019
 * Time: 16:38
 */

namespace Heidelpay\Tests\PhpPaymentApi\Helper;


use Heidelpay\PhpPaymentApi\Constants\CommercialSector;
use Heidelpay\PhpPaymentApi\Constants\RegistrationType;

class Company
{
    protected $companyName = 'heidelpay GmbH';
    protected $poBox = 'poBox';
    protected $street = 'street';
    protected $zip = 'zip';
    protected $city = 'city';
    protected $country = 'country';
    protected $commercialSector = CommercialSector::AIR_TRANSPORT;
    protected $registrationType = RegistrationType::REGISTERED;
    protected $commercialRegisterNumber = '123456789';
    protected $vatId = '123456';
    protected  $executive = [null];

    public function getCompanyDataArray()
    {
        return [
            $this->companyName,
            $this->poBox,
            $this->street,
            $this->zip,
            $this->city,
            $this->country,
            $this->commercialSector,
            $this->registrationType,
            $this->commercialRegisterNumber,
            $this->vatId,
            $this->executive
        ];

    }

    /**
     * @param null $companyName
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;
        return $this;
    }

    /**
     * @param null $poBox
     */
    public function setPoBox($poBox)
    {
        $this->poBox = $poBox;
        return $this;
    }

    /**
     * @param null $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
        return $this;
    }

    /**
     * @param null $zip
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
        return $this;
    }

    /**
     * @param null $city
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @param null $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @param null $commercialSector
     */
    public function setCommercialSector($commercialSector)
    {
        $this->commercialSector = $commercialSector;
        return $this;
    }

    /**
     * @param null $registrationType
     */
    public function setRegistrationType($registrationType)
    {
        $this->registrationType = $registrationType;
        return $this;
    }

    /**
     * @param null $commercialRegisterNumber
     */
    public function setCommercialRegisterNumber($commercialRegisterNumber)
    {
        $this->commercialRegisterNumber = $commercialRegisterNumber;
        return $this;
    }

    /**
     * @param null $vatId
     */
    public function setVatId($vatId)
    {
        $this->vatId = $vatId;
        return $this;
    }

    /**
     * @param array $executive
     */
    public function setExecutive($executive)
    {
        $this->executive = $executive;
        return $this;
    }

}