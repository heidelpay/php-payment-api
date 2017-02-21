<?php
namespace Heidelpay\PhpApi\ParameterGroups;

/**
 * This class provides every api parameter related to the customers billingaddress data
 *
 * Depending on the used payment method billing and shipping address should
 * be equal.
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  https://dev.heidelpay.de/PhpApi
 *
 * @author  Jens Richter
 *
 * @package  Heidelpay
 * @subpackage PhpApi
 * @category PhpApi
 */
class AddressParameterGroup extends AbstractParameterGroup
{
    
    /**
     * @var string city of the customers billingaddress (mandatory)
     */
    public $city = null;
    
    /**
     * @var string county of the customers billingaddress in ISO 3166-1 2 digits (mandatory)
     */
    public $country = null;
    /**
     * @var string state of the customers billingaddress in ISO 3166-2 (optinal)
     */
    public $state = null;
    /**
     * @var string street of the customers billingaddress (mandatory)
     */
    public $street = null;
    /**
     * @var string zip code of the customers billingaddress (mandatory)
     */
    public $zip = null;
    
    /**
     * AddressCity getter
     *
     * @return string city
     */
    public function getCity()
    {
        return $this->city;
    }
     
     /**
     * AddressCountry getter
     *
     * @return string country
     */
    public function getCountry()
    {
        return $this->country;
    }
    
    /**
     * AddressState getter
     *
     * @return string state
     */
    public function getState()
    {
        return $this->state;
    }
    
    /**
     * AddressStreet getter
     *
     * @return string street
     */
    public function getStreet()
    {
        return $this->street;
    }
    
    /**
     * AddressZip getter
     *
     * @return string zip
     */
    public function getZip()
    {
        return $this->zip;
    }
}
