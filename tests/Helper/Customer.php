<?php
/**
 * Created by PhpStorm.
 * User: Simon.Gabriel
 * Date: 24.10.2017
 * Time: 14:00
 */
namespace Heidelpay\Tests\PhpPaymentApi\Helper;

class Customer
{
    //<editor-fold desc="Properties">

    /** @var string $givenName */
    protected $givenName = 'Heidel';

    /** @var string $familyName */
    protected $familyName = 'Berger-Payment';

    /** @var string $companyName */
    protected $companyName;

    /** @var string $shopperId */
    protected $shopperId = '1234';

    /** @var string $addressStreet */
    protected $addressStreet = 'Vagerowstr. 18';

    /** @var string $addressState */
    protected $addressState = 'DE-BW';

    /** @var string $addressZip */
    protected $addressZip = '69115';

    /** @var string $addressCity */
    protected $addressCity = 'Heidelberg';

    /** @var string $addressCountry */
    protected $addressCountry = 'DE';

    /** @var string $customerEmail */
    protected $customerEmail = 'development@heidelpay.com';

    //</editor-fold>

    /**
     * Return customer details array
     *
     * @return array
     */
    public function getCustomerDataArray()
    {
        return [
            $this->givenName,
            $this->familyName,
            $this->companyName,
            $this->shopperId,
            $this->addressStreet,
            $this->addressState,
            $this->addressZip,
            $this->addressCity,
            $this->addressCountry,
            $this->customerEmail
        ];
    }

    //<editor-fold desc="Getters/Setters">

    /**
     * @param string $givenName
     *
     * @return Customer
     */
    public function setGivenName($givenName)
    {
        $this->givenName = $givenName;
        return $this;
    }

    /**
     * @param string $familyName
     *
     * @return Customer
     */
    public function setFamilyName($familyName)
    {
        $this->familyName = $familyName;
        return $this;
    }

    /**
     * @param string $companyName
     *
     * @return Customer
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;
        return $this;
    }

    /**
     * @param string $shopperId
     *
     * @return Customer
     */
    public function setShopperId($shopperId)
    {
        $this->shopperId = $shopperId;
        return $this;
    }

    /**
     * @param string $addressStreet
     *
     * @return Customer
     */
    public function setAddressStreet($addressStreet)
    {
        $this->addressStreet = $addressStreet;
        return $this;
    }

    /**
     * @param string $addressState
     *
     * @return Customer
     */
    public function setAddressState($addressState)
    {
        $this->addressState = $addressState;
        return $this;
    }

    /**
     * @param string $addressZip
     *
     * @return Customer
     */
    public function setAddressZip($addressZip)
    {
        $this->addressZip = $addressZip;
        return $this;
    }

    /**
     * @param string $addressCity
     *
     * @return Customer
     */
    public function setAddressCity($addressCity)
    {
        $this->addressCity = $addressCity;
        return $this;
    }

    /**
     * @param string $addressCountry
     *
     * @return Customer
     */
    public function setAddressCountry($addressCountry)
    {
        $this->addressCountry = $addressCountry;
        return $this;
    }

    /**
     * @param string $customerEmail
     *
     * @return Customer
     */
    public function setCustomerEmail($customerEmail)
    {
        $this->customerEmail = $customerEmail;
        return $this;
    }

    //</editor-fold>
}
