<?php

namespace Heidelpay\PhpPaymentApi\ParameterGroups;

use Heidelpay\PhpPaymentApi\Constants\ExecutiveFunction;

/**
 * This class provides every api parameter related to the company data
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
class CompanyParameterGroup extends AbstractParameterGroup
{
    /**
     * Name of the company
     *
     * @var string
     */
    public $companyname;

    /** Registration type
     * Allowed values: "REGISTERED" , "UNREGISTERED"
     *
     * @var string
     */
    public $registrationtype;

    /**
     * @var string
     */
    public $commercialregisternumber;

    /**
     * @var string
     */
    public $vatid;

    /**
     * array of Executives
     *
     * @var array
     */
    public $executive;

    /**
     * Commercial sector of company
     *
     * @var string
     */
    public $commercialsector;

    /**
     * Company location Parametergroup
     *
     * @var LocationParameterGroup
     */
    public $location;

    /**
     * Function to add an executive to the existing list
     *
     * @param string             $function
     * @param string             $salutation
     * @param string             $given
     * @param string             $family
     * @param string             $birthdate
     * @param string             $email
     * @param string             $phone
     * @param HomeParameterGroup $home
     * @param mixed              $homeStreet
     * @param mixed              $homeZip
     * @param mixed              $homeCity
     * @param mixed              $homeCountry
     *
     * @return CompanyParameterGroup
     */
    public function addExecutive(
        $salutation,
        $given,
        $family,
        $birthdate,
        $email,
        $phone,
        $homeStreet,
        $homeZip,
        $homeCity,
        $homeCountry,
        $function = ExecutiveFunction::OWNER
    ) {
        $newExecutive = new ExecutiveParameterGroup();
        $newExecutive->setFunction(!empty($function) && is_string($function) ?$function: ExecutiveFunction::OWNER);
        $newExecutive->setSalutation($salutation);
        $newExecutive->setGiven($given);
        $newExecutive->setFamily($family);
        $newExecutive->setBirthdate($birthdate);
        $newExecutive->setEmail($email);
        $newExecutive->setPhone($phone);

        $home = $newExecutive->getHome();

        $home->street = $homeStreet;
        $home->city = $homeCity;
        $home->country =$homeCountry;
        $home->zip = $homeZip;

        $executives = $this->getExecutive();
        $executives[] = $newExecutive;
        $this->setExecutive($executives);

        return $this;
    }

    /**
     * Company executive getter
     *
     * @return array
     *
     * @param null|mixed $index
     */
    public function getExecutive($index = null)
    {
        if ($this->executive === null) {
            return $this->executive = [];
        }

        if ($index !== null) {
            if (!is_bool($index) && isset($this->executive[$index])) {
                return $this->executive[$index];
            } else {
                return null;
            }
        }

        return $this->executive;
    }

    /**
     * Setter for company executive
     *
     * @param array $executive
     */
    public function setExecutive($executive)
    {
        $this->executive = $executive;
        return $this;
    }

    /**
     * Company location getter
     *
     * @return LocationParameterGroup
     */
    public function getLocation()
    {
        if ($this->location === null) {
            return $this->location = new LocationParameterGroup();
        }
        return $this->location;
    }

    /**
     * Setter for company location
     *
     * @param LocationParameterGroup $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
        return $this;
    }

    /**
     * Companyname getter
     *
     * @return string | null
     */
    public function getCompanyname()
    {
        return $this->companyname;
    }

    /**
     * Setter for companyname
     *
     * @param string $companyname
     */
    public function setCompanyname($companyname)
    {
        $this->companyname = $companyname;
        return $this;
    }

    /**
     * Company registrationtype getter
     *
     * @return string | null
     */
    public function getRegistrationtype()
    {
        return $this->registrationtype;
    }

    /**
     * Setter for company registrationtype
     *
     * @param string $registrationtype
     */
    public function setRegistrationtype($registrationtype)
    {
        $this->registrationtype = $registrationtype;
        return $this;
    }

    /**
     * Commercialregistrnumber getter
     *
     * @return string | null
     */
    public function getCommercialregisternumber()
    {
        return $this->commercialregisternumber;
    }

    /**
     * Setter for commercialregistrnumber
     *
     * @param string $commercialregisternumber
     */
    public function setCommercialregisternumber($commercialregisternumber)
    {
        $this->commercialregisternumber = $commercialregisternumber;
        return $this;
    }

    /**
     * Company vatid getter
     *
     * @return string | null
     */
    public function getVatid()
    {
        return $this->vatid;
    }

    /**
     * Setter for vatid
     *
     * @param string $vatid
     */
    public function setVatid($vatid)
    {
        $this->vatid = $vatid;
        return $this;
    }

    /**
     * Commercialsector getter
     *
     * @return string | null
     */
    public function getCommercialSector()
    {
        return $this->commercialsector;
    }

    /**
     * Setter commerialSector
     *
     * @param string $commercialSector
     */
    public function setCommercialSector($commercialSector)
    {
        $this->commercialsector = $commercialSector;
        return $this;
    }
}
