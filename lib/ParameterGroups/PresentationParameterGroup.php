<?php

namespace Heidelpay\PhpApi\ParameterGroups;

/**
 * This class provides the api parameter for amount and currency.
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
class PresentationParameterGroup extends AbstractParameterGroup
{
    /**
     * PresentationAmount
     *
     * Order or transaction amount.
     *
     * @var float amount (mandatory)
     */
    public $amount = null;

    /**
     * PresentationCurrency
     *
     * Currency of the transaction. ISO 4217
     *
     * @var string currency code ISO 4217 (mandatory)
     */
    public $currency = null;

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
    public $usage = null;

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
}
