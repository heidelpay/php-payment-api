<?php

namespace Heidelpay\PhpPaymentApi\ParameterGroups;

/**
 * This class provides every api parameter related to the customers account data
 *
 * The Account group holds all information regarding a credit card or bank account.
 * Many parameters depend on the chosen payment method.
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-payment-api/
 *
 * @author  Jens Richter
 *
 * @package heidelpay\php-payment-api\parameter-groups
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
    public $bank;

    /**
     * Bankname - Especially of interest for OnlineTransfer methods to determine which bank was chosen.
     *
     * @var string bankname of the given account
     */
    public $bankname;

    /**
     * Brand name of the given account data (for example iDeal)
     *
     * @var string brand of the given account
     */
    public $brand;

    /**
     * Bic - Business identifier code used for non sepa direct debit
     *
     * @var string bic of the given accout
     */
    public $bic;

    /**
     * Country - Bank or Account Country
     *
     * @var string country of the given account
     */
    public $country;

    /**
     * Expiry month used for credit and debit cards
     *
     * @var string expiry month of the given account
     */
    public $expiry_month;

    /**
     * Expiry year used for credit and debit cards
     *
     * @var string expiry year of the given account
     */
    public $expiry_year;

    /**
     * Owner of the given account data
     *
     * @var string holder of the given account
     */
    public $holder;

    /**
     * International bank account number
     *
     * @var string iban of the given account
     */
    public $iban;

    /**
     * Identification - Used for SEPA mandate ID
     *
     * @var string sepa mandate id from the payment response
     */
    public $identification;

    /**
     * Account number can be used for non sepa direct debit transactions
     *
     * @var string number of the given account
     */
    public $number;


    /**
     * @var string verification of the given account
     */
    public $verification;

    /**
     * AccountBank getter
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
     * Setter for the bank number of the account
     *
     * @param $bank string
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\AccountParameterGroup
     *
     * @deprecated please use IBan and Bic instead
     */
    public function setBank($bank)
    {
        $this->bank = $bank;
        return $this;
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
     * Setter for the bankname of the used account
     *
     * @param $bankName string
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\AccountParameterGroup
     */
    public function setBankName($bankName)
    {
        $this->bankname = $bankName;
        return $this;
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
     * Setter for the account brand name, e.g. SOFORT or PAYPAL
     *
     * @param $brand string name of the used brand in upper case
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\AccountParameterGroup
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;
        return $this;
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
     * Setter for the sepa bank account identifier
     *
     * @param string $bic
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\AccountParameterGroup
     */
    public function setBic($bic)
    {
        $this->bic = $bic;
        return $this;
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
     * Setter for the country code of the used account
     *
     * @param string $country account country
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\AccountParameterGroup
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
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
     * Setter for the holders name of a given account
     *
     * @param string $holder account holder
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\AccountParameterGroup
     */
    public function setHolder($holder)
    {
        $this->holder = $holder;
        return $this;
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
     * Setter for the sepa iban of a given account
     *
     * @param $iban string iban of the account
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\AccountParameterGroup
     */
    public function setIban($iban)
    {
        $this->iban = $iban;
        return $this;
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
     *
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Setter for the number of a given account
     *
     * Note: due to pci restrictions you are not allowed to use this setter for credit or debit
     * card information.
     *
     * @param $number string number of the use account
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\AccountParameterGroup
     */
    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
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

    /**
     * @param string $expiry_month
     *
     * @return AccountParameterGroup
     */
    public function setExpiryMonth($expiry_month)
    {
        $this->expiry_month = $expiry_month;
        return $this;
    }

    /**
     * @param string $expiry_year
     *
     * @return AccountParameterGroup
     */
    public function setExpiryYear($expiry_year)
    {
        $this->expiry_year = $expiry_year;
        return $this;
    }

    /**
     * @param string $verification
     *
     * @return AccountParameterGroup
     */
    public function setVerification($verification)
    {
        $this->verification = $verification;
        return $this;
    }

    /**
     * @param string $identification
     *
     * @return AccountParameterGroup
     */
    public function setIdentification($identification)
    {
        $this->identification = $identification;
        return $this;
    }
}
