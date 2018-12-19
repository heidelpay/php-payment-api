<?php
/**
 * Created by PhpStorm.
 * User: David.Owusu
 * Date: 22.11.2018
 * Time: 17:44
 */
namespace Heidelpay\PhpPaymentApi\ParameterGroups;

/**
 * This class provides every api parameter related to the company account data
 *
 * The Account group holds all information regarding a credit card or bank account.
 * Many parameters depend on the chosen payment method.
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
    public function __construct()
    {
        $this->executive = [null];
    }

    /**
     * @var string company name.
     */
    public $companyname;

    public $registrationtype;

    public $commercialregisternumber;

    public $vatid;

    /**
     * @var array $executive contains an array of Executives
     */
    public $executive;

    public $commercialSector;

    /**
     * @var \Heidelpay\PhpPaymentApi\ParameterGroups\LocationParameterGroup
     */
    public $location;

    /**
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
     * @param LocationParameterGroup $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return string
     */
    public function getCompanyname()
    {
        return $this->companyname;
    }

    /**
     * @param string $companyname
     */
    public function setCompanyname($companyname)
    {
        $this->companyname = $companyname;
    }

    /**
     * @return mixed
     */
    public function getRegistrationtype()
    {
        return $this->registrationtype;
    }

    /**
     * @param mixed $registrationtype
     */
    public function setRegistrationtype($registrationtype)
    {
        $this->registrationtype = $registrationtype;
    }

    /**
     * @return mixed
     */
    public function getCommercialregisternumber()
    {
        return $this->commercialregisternumber;
    }

    /**
     * @param mixed $commercialregisternumber
     */
    public function setCommercialregisternumber($commercialregisternumber)
    {
        $this->commercialregisternumber = $commercialregisternumber;
    }

    /**
     * @return mixed
     */
    public function getVatid()
    {
        return $this->vatid;
    }

    /**
     * @param mixed $vatid
     */
    public function setVatid($vatid)
    {
        $this->vatid = $vatid;
    }

    /**
     * @return mixed
     */
    public function getExecutive()
    {
        return $this->executive;
    }

    /**
     * @param array $executive
     */
    public function setExecutive($executive)
    {
        $this->executive = $executive;
    }

    /**
     * @return mixed
     */
    public function getCommercialSector()
    {
        return $this->commercialSector;
    }

    /**
     * @param mixed $commercialSector
     */
    public function setCommercialSector($commercialSector)
    {
        $this->commercialSector = $commercialSector;
    }
}
