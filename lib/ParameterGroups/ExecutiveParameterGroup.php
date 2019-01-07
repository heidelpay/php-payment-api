<?php
/**
 * Created by PhpStorm.
 * User: David.Owusu
 * Date: 26.11.2018
 * Time: 11:26
 */
namespace Heidelpay\PhpPaymentApi\ParameterGroups;

class ExecutiveParameterGroup extends AbstractParameterGroup
{
    public $function;

    public $salutation;

    public $given;

    public $family;

    public $birthdate;

    public $email;

    public $phone;

    public $home;

    /**
     * @return mixed
     */
    public function getFunction()
    {
        return $this->function;
    }

    /**
     * @param mixed $function
     */
    public function setFunction($function)
    {
        $this->function = $function;
    }

    /**
     * @return mixed
     */
    public function getSalutation()
    {
        return $this->salutation;
    }

    /**
     * @param mixed $salutation
     */
    public function setSalutation($salutation)
    {
        $this->salutation = $salutation;
    }

    /**
     * @return mixed
     */
    public function getGiven()
    {
        return $this->given;
    }

    /**
     * @param mixed $given
     */
    public function setGiven($given)
    {
        $this->given = $given;
    }

    /**
     * @return mixed
     */
    public function getFamily()
    {
        return $this->family;
    }

    /**
     * @param mixed $family
     */
    public function setFamily($family)
    {
        $this->family = $family;
    }

    /**
     * @return mixed
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * @param mixed $birthdate
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }


    /**
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
     * @param mixed $home
     */
    public function setHome($home)
    {
        $this->home = $home;
    }
}
