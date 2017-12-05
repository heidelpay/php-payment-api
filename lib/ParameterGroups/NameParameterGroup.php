<?php

namespace Heidelpay\PhpPaymentApi\ParameterGroups;

/**
 * This class provides every api parameter of the "name" namespace
 *
 * It will be used for parameters like given name, but also for salutation etc.
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author  Jens Richter
 *
 * @package  Heidelpay
 * @subpackage PhpPaymentApi
 * @category PhpPaymentApi
 */
class NameParameterGroup extends AbstractParameterGroup
{
    /**
     * NameBirthdate
     *
     * Date in the format YYYY-MM-DD e.g. 1970-09-12
     *
     * @var string company name (optional)
     */
    public $birthdate;

    /**
     * NameCompany
     *
     * Used to set the company name of your customer.
     *
     * @var string company name (optional)
     */
    public $company;

    /**
     * NameGiven
     *
     * First name of your customer.
     *
     * @var string given name of the customer (mandatory)
     */
    public $given;

    /**
     * NameFamily
     *
     * Family name of your customer
     *
     * @var string family name of the customer (mandatory)
     */
    public $family;


    /**
     * NameSalutation
     *
     * Salutation of the customer, may be used as value for place holders (e.g. in dunning mail templates)
     *
     * @var string salutation of the customer (MR/MRS) (conditional mandatory)
     */
    public $salutation;


    /**
     * NameTitle
     *
     * Title of the customer
     *
     * @var string tile of the customer (optional)
     */
    public $title;

    /**
     * NameBirthdate getter
     *
     * @return string title
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * NameCompany getter
     *
     * @return string company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * NameGiven getter
     *
     * @return string given
     */
    public function getGiven()
    {
        return $this->given;
    }

    /**
     * NameFamily getter
     *
     * @return string family
     */
    public function getFamily()
    {
        return $this->family;
    }

    /**
     * NameSalutation getter
     *
     * @return string salutation
     */
    public function getSalutation()
    {
        return $this->salutation;
    }

    /**
     * NameTitle getter
     *
     * @return string title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Setter for the brithdate of the customer
     *
     * @param string $birthdate notation is YYYY-MM-DD
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\NameParameterGroup
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;
        return $this;
    }

    /**
     * Setter for the company name
     *
     * @param string $company f.e. Heidelberger Payment GmbH
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\NameParameterGroup
     */
    public function setCompany($company)
    {
        $this->company = $company;
        return $this;
    }

    /**
     * Setter for the given name of the customer
     *
     * @param string $given f.e. John
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\NameParameterGroup
     */
    public function setGiven($given)
    {
        $this->given = $given;
        return $this;
    }

    /**
     * Setter for the family name of the customer
     *
     * @param string $family f.e. Doe
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\NameParameterGroup
     */
    public function setFamily($family)
    {
        $this->family = $family;
        return $this;
    }

    /**
     * Setter for the salutation of the customer
     *
     * @param string $salutation f.e. MR
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\NameParameterGroup
     */
    public function setSalutation($salutation)
    {
        $this->salutation = strtoupper($salutation);
        return $this;
    }

    /**
     * Setter for the title of the customer
     *
     * @param string $title f.e. Doc.
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\NameParameterGroup
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
}
