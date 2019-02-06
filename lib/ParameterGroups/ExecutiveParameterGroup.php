<?php

namespace Heidelpay\PhpPaymentApi\ParameterGroups;

/**
 * This class provides every api parameter related to the company executive data
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
class ExecutiveParameterGroup extends AbstractParameterGroup
{
    /**
     * The function of the executive in the company. Default: "OWNER"
     * Possible other values are: "PARTNER", "SHAREHOLDER", "DIRECTOR", "MANAGER", "REGISTERED_MANAGER".
     *
     * @var string
     */
    public $function;

    /**
     * Executive salutation
     *
     * @var string
     */
    public $salutation;

    /**
     * First name of the executive
     *
     * @var string
     */
    public $given;

    /**
     * Family name of the executive
     *
     * @var string
     */
    public $family;

    /**
     * Executive birthdate
     *
     * @var string
     */
    public $birthdate;

    /**
     * Executive email
     *
     * @var string
     */
    public $email;

    /**
     * Executive phone number
     *
     * @var string
     */
    public $phone;

    /**
     * Executive home parametergroup
     *
     * @var HomeParameterGroup
     */
    public $home;

    /**
     * Executive function getter
     *
     * @return string | null
     */
    public function getFunction()
    {
        return $this->function;
    }

    /**
     * Setter for executive function
     *
     * @param mixed $function
     */
    public function setFunction($function)
    {
        $this->function = $function;
        return $this;
    }

    /**
     * Executive salutation getter
     *
     * @return string | null
     */
    public function getSalutation()
    {
        return $this->salutation;
    }

    /**
     * Setter for executive salutation
     *
     * @param mixed $salutation
     */
    public function setSalutation($salutation)
    {
        $this->salutation = $salutation;
        return $this;
    }

    /**
     * Executive name given getter
     *
     * @return string | null
     */
    public function getGiven()
    {
        return $this->given;
    }

    /**
     * Setter for executive name given
     *
     * @param mixed $given
     */
    public function setGiven($given)
    {
        $this->given = $given;
        return $this;
    }

    /**
     * Executive family name getter
     *
     * @return string | null
     */
    public function getFamily()
    {
        return $this->family;
    }

    /**
     * Setter for executive family name
     *
     * @param mixed $family
     */
    public function setFamily($family)
    {
        $this->family = $family;
        return $this;
    }

    /**
     * Executive birthdate getter
     *
     * @return string | null
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Setter for executive birthdate
     *
     * @param string $birthdate format:YYYY-MMM-DD
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;
        return $this;
    }

    /**
     * Executive email getter
     *
     * @return string | null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Setter for executive email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Executive phone number getter
     *
     * @return string | null
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Setter for executive phone number
     *
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * Executive home getter
     *
     * @return HomeParameterGroup
     */
    public function getHome()
    {
        if ($this->home === null) {
            return $this->home = new HomeParameterGroup();
        }
        return $this->home;
    }

    /**
     * Setter for executive home
     *
     * @param HomeParameterGroup $home
     */
    public function setHome($home)
    {
        $this->home = $home;
        return $this;
    }
}
