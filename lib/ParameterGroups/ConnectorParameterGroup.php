<?php

namespace Heidelpay\PhpPaymentApi\ParameterGroups;

/**
 * This class provides api parameter related to the connector group
 *
 * The Connector group delivers information about the connector which was used
 * for the processing of the transaction.
 *
 * This is for instance relevant for prepayment or invoice transactions.
 * Depending on the used connector a merchant may want to offer different bank accounts for a referral.
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
class ConnectorParameterGroup extends AbstractParameterGroup
{
    /**
     * ConnectorAccountBank
     *
     * Bank Identifier Code (SWIFT). This bank code can be used by
     * foreign customers to make cross-border credit transfers.
     *
     * @var string account bank
     *
     * @deprecated please use IBan and Bic instead
     */
    public $account_bank;

    /**
     * ConnectorAccountBic
     *
     * The domestic code of the bank which holds the direct debit or
     * credit transfer account.
     *
     * @var string account bic
     */
    public $account_bic;

    /**
     * ConnectorAccountCountry
     *
     * Country code according to the ISO 3166-1 specification.
     *
     * @var string account country
     */
    public $account_country;

    /**
     * ConnectorAccountHolder
     *
     * Holder of the bank account. This is generally a company name.
     *
     * @var string account holder
     */
    public $account_holder;

    /**
     * ConnectorAccountIBan
     *
     * International Bank Account Number. This number can be
     * used by foreign customers to make cross-border credit transfers.
     *
     * @var string account holder
     */
    public $account_iban;

    /**
     * ConnectorAccountNumber
     *
     * Account number of the processing bank account. Includes also possible check digits.
     *
     * @var string account number
     *
     * @deprecated please use IBan and Bic instead
     */
    public $account_number;

    /**
     * @var string account usage
     */
    public $account_usage;

    /**
     * Account bank getter
     *
     * @return string account bank
     *
     * @deprecated please use IBan and Bic instead
     */
    public function getAccountBank()
    {
        return $this->account_bank;
    }

    /**
     * Account bic getter
     *
     * @return string account bic
     */
    public function getAccountBic()
    {
        return $this->account_bic;
    }

    /**
     * Account country getter
     *
     * @return string account country
     */
    public function getAccountCountry()
    {
        return $this->account_country;
    }

    /**
     * Account holder getter
     *
     * @return string account holder
     */
    public function getAccountHolder()
    {
        return $this->account_holder;
    }

    /**
     * Account IBan getter
     *
     * @return string account IBan
     */
    public function getAccountIBan()
    {
        return $this->account_iban;
    }

    /**
     * Account number getter
     *
     * @return string account number
     *
     * @deprecated please use IBan and Bic instead
     */
    public function getAccountNumber()
    {
        return $this->account_number;
    }

    /**
     * Returns the Account Usage.
     *
     * @return string account usage
     */
    public function getAccountUsage()
    {
        return $this->account_usage;
    }
}
