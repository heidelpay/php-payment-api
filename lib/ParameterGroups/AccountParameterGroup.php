<?php
namespace Heidelpay\PhpApi\ParameterGroups;
use \Heidelpay\PhpApi\ParameterGroups\AbstractParameterGroup;

/**
 * This class provides every api parmater related to the customers account data  
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 * @link  https://dev.heidelpay.de/PhpApi
 * @author  Jens Richter
 * 
 * @package  Heidelpay
 * @subpackage PhpApi
 * @category PhpApi
 */


class AccountParameterGroup extends AbstractParameterGroup{
    
    /**
     * @var string brand of the given accout
     */
    public $brand = NULL;
    
    /**
     * @var string expiry month of the given accout
     */
    public $expiry_month = NULL;
    
    /**
     * @var string expiry year of the given accout
     */
    public $expiry_year = NULL;
    
   /**
     * @var string holder of the given accout 
     */
    public $holder = NULL;
    
    /**
     * @var string number of the given accout
     */
    public $number = NULL;
    
    
    /**
     * @var string verification of the given accout
     */
    public $verification = NULL;
    
    /**
     *  AccountBrand getter
     * @return string brand
     */
    
    public function getBrand(){
        return $this->brand;
    }
    
    /**
     * AccountExpiryMonth getter
     * @return string expiry month
     */
    
    public function getExpiryMonth(){
        return $this->expiry_month;
    }
    
    /**
     * AccountExpiryYear getter
     * @return string expiry year
     */
    
    public function getExpiryYear(){
        return $this->expiry_year;
    }
    
    /**
     * AccountHolder getter
     * @return string holder
     */
    
    public function getHolder(){
        return $this->holder;
    }
    
    /**
     * AccountNumber getter
     * @return string number
     */
    
    public function getNumber(){
        return $this->number;
    }
    
}