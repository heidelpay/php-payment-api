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
     * Brand name of the given accound data (for example iDeal)
     * @var string brand of the given accout
     */
    public $brand = NULL;
    
    /**
     * Bic - Business identifier code used for non sepa direct debit
     * @var string bic of the given accout
     */
    public $bic = NULL;
    
    /**
     * Expiry month used for credit and debit cards
     * @var string expiry month of the given accout
     */
    public $expiry_month = NULL;
    
    /**
     * Expiry year used for credit and debit cards
     * @var string expiry year of the given accout
     */
    public $expiry_year = NULL;
    
   /**
    * Owner of the given account data
     * @var string holder of the given accout 
     */
    public $holder = NULL;
    
    /**
     * International bank account number
     * @var string iban of the given accout
     */
    public $iban = NULL;
    
    /**
     * Account number can be used for non sepa direct debit transactions
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
     * AccountIban getter
     * @return string iban
     */
    
    public function getIban(){
        return $this->iban;
    }
    
    /**
     * AccountNumber getter
     * @return string number
     */
    
    public function getNumber(){
        return $this->number;
    }
    
}