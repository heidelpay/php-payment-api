<?php

namespace Heidelpay\PhpPaymentApi\ParameterGroups;

/**
 * This class provides every api parameter related to the customers risk factors
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-payment-api/
 *
 * @author  Daniel Kraut
 *
 * @package heidelpay\php-payment-api\parameter-groups
 */
class RiskInformationParameterGroup extends AbstractParameterGroup
{
    /**
     * @var bool if guest checkout (true/false) (optional)
     */
    public $customerGuestCheckout;

    /**
     * @var string first date of customers relationship (YYYY-MM-DD) (optional)
     */
    public $customerSince;

    /**
     * @var integer of customer's order count (optional)
     */
    public $customerOrderCount;

    /**
     * Guestcheckout getter
     *
     * @return bool state
     */
    public function getCustomerGuestCheckout()
    {
        return $this->customerGuestCheckout;
    }

    /**
     * Since getter
     *
     * @return string since
     */
    public function getCustomerSince()
    {
        return $this->customerSince;
    }

    /**
     * Ordercount getter
     *
     * @return int ordercount
     */
    public function getCustomerOrderCount()
    {
        return $this->customerOrderCount;
    }

    /**
     * setter for is guest checkout
     *
     * @param string $customerGuestCheckout
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\RiskInformationParameterGroup
     */
    public function setCustomerGuestCheckout($customerGuestCheckout)
    {
        $this->customerGuestCheckout = $customerGuestCheckout;
        return $this;
    }

    /**
     * setter for customer since
     *
     * @param string $customerSince
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\RiskInformationParameterGroup
     */
    public function setCustomerSince($customerSince)
    {
        $this->customerSince = $customerSince;
        return $this;
    }

    /**
     * setter for order count
     *
     * @param int $customerOrderCount
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\RiskInformationParameterGroup
     */
    public function setCustomerOrderCount($customerOrderCount)
    {
        $this->customerOrderCount = $customerOrderCount;
        return $this;
    }
}
