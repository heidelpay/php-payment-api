<?php
namespace Heidelpay\PhpApi\ParameterGroups;

/**
 * This class provides every api parameter related to the customers account data
 *
 * The Account group holds all information regarding a credit card or bank account.
 * Many parameters depend on the chosen payment method.
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
class AccountParameterGroup extends AbstractParameterGroup
{
    /**
     * Bank - The domestic code of the bank which holds the direct debit or credit transfer account.
     *
     * @var string bank of the given account
     *
     * @deprecated please use IBan and Bic instead
     */
    public $bank = null;

    /**
     * Bankname - Especially of interest for OnlineTransfer methods to determine which bank was chosen.
     *
     * @var string bankname of the given account
     */
    public $bankname = null;

    /**
     * Brand name of the given account data (for example iDeal)
     *
     * @var string brand of the given account
     */
    public $brand = null;
    
    /**
     * Bic - Business identifier code used for non sepa direct debit
     *
     * @var string bic of the given accout
     */
    public $bic = null;

    /**
     * Country - Bank or Account Country
     *
     * @var string country of the given account
     */
    public $country = null;
    
    /**
     * Expiry month used for credit and debit cards
     *
     * @var string expiry month of the given account
     */
    public $expiry_month = null;
    
    /**
     * Expiry year used for credit and debit cards
     *
     * @var string expiry year of the given account
     */
    public $expiry_year = null;
    
   /**
    * Owner of the given account data
    *
     * @var string holder of the given account
     */
    public $holder = null;
    
    /**
     * International bank account number
     *
     * @var string iban of the given account
     */
    public $iban = null;

    /**
     * Identification - Used for SEPA mandate ID
     *
     * @var string sepa mandate id from the payment response
     */
    public $identification = null;
    
    /**
     * Account number can be used for non sepa direct debit transactions
     *
     * @var string number of the given account
     */
    public $number = null;
    
    
    /**
     * @var string verification of the given account
     */
    public $verification = null;

    /**
     *  AccountBank getter
     *
     * @return string bank
     *
     * @deprecated  please use IBan and Bic instead
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     *  AccountBankname getter
     *
     * @return string bankname
     */
    public function getBankName()
    {
        return $this->bankname;
    }
    
    /**
     *  AccountBrand getter
     *
     * @return string brand
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     *  AccountBic getter
     *
     * @return string bic
     */
    public function getBic()
    {
        return $this->bic;
    }

    /**
     *  AccountCountry getter
     *
     * @return string country
     */
    public function getCountry()
    {
        return $this->country;
    }
    
    /**
     * AccountExpiryMonth getter
     *
     * @return string expiry month
     */
    public function getExpiryMonth()
    {
        return $this->expiry_month;
    }
    
    /**
     * AccountExpiryYear getter
     *
     * @return string expiry year
     */
    public function getExpiryYear()
    {
        return $this->expiry_year;
    }
    
    /**
     * AccountHolder getter
     *
     * @return string holder
     */
    public function getHolder()
    {
        return $this->holder;
    }
    
    /**
     * AccountIban getter
     *
     * @return string iban
     */
    public function getIban()
    {
        return $this->iban;
    }

    /**
     * AccountIdentification getter
     *
     * @return string identification
     */
    public function getIdentification()
    {
        return $this->identification;
    }
    
    /**
     * AccountNumber getter
     *
     * @return string number
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * AccountVerification getter
     *
     * @return string verification
     */
    public function getVerification()
    {
        return $this->verification;
    }
}
