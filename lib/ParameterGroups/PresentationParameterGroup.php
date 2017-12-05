<?php

namespace Heidelpay\PhpPaymentApi\ParameterGroups;

/**
 * This class provides the api parameter for amount and currency.
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
class PresentationParameterGroup extends AbstractParameterGroup
{
    /**
     * PresentationAmount
     *
     * Order or transaction amount.
     *
     * @var float amount (mandatory)
     */
    public $amount;

    /**
     * PresentationCurrency
     *
     * Currency of the transaction. ISO 4217
     *
     * @var string currency code ISO 4217 (mandatory)
     */
    public $currency;

    /**
     * PresentationUsage
     *
     * Provides the dynamic part of the descriptor, which appears on the
     * customer’s statement. Enables the end customer to associate the
     * transaction on the statement to the online transaction.
     *
     * If a dynamic descriptor can be set, depends on the used connector.
     *
     * @var string usage
     */
    public $usage;

    /**
     * PresentationAmount getter
     *
     * @return string amount
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * PresentationCurrency getter
     *
     * @return string currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * PresentationUsage getter
     *
     * @return string usage
     */
    public function getUsage()
    {
        return $this->usage;
    }

    /**
     * Setter for the basket amount
     *
     * @param float $amount f.e. 20.12
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\PresentationParameterGroup
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * Setter for the currency in iso code 3 letters
     *
     * @param string $currency f.e USD
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\PresentationParameterGroup
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * Setter for the usage
     *
     * The usage text on the customers account can be set for some payment methods
     *
     * @param string $usage f.e. "Order 1232. Thank you for choosing Heidelpay"
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\PresentationParameterGroup
     */
    public function setUsage($usage)
    {
        $this->usage = $usage;
        return $this;
    }
}
