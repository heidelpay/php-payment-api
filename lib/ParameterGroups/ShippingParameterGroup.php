<?php

namespace Heidelpay\PhpPaymentApi\ParameterGroups;

/**
 * This class provides the api parameter for shipping addresses.
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-payment-api/
 *
 * @author  Marcus Kreusch
 *
 * @package heidelpay\php-payment-api\parameter-groups
 */
class ShippingParameterGroup extends AbstractParameterGroup
{
    /**
     * ShippingAddress
     *
     * shipping address subgroup
     *
     * @var \Heidelpay\PhpPaymentApi\ParameterGroups\AddressParameterGroup
     */
    public $address;

    /**
     * ShippingName
     *
     * shipping name subgroup
     *
     * @var \Heidelpay\PhpPaymentApi\ParameterGroups\NameParameterGroup
     */
    public $name;

    /**
     * ShippingAddress getter
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\AddressParameterGroup
     */
    public function getAddress()
    {
        if ($this->address === null) {
            return $this->address = new AddressParameterGroup();
        }

        return $this->address;
    }

    /**
     * ShippingName getter
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\NameParameterGroup
     */
    public function getName()
    {
        if ($this->name === null) {
            return $this->name = new NameParameterGroup();
        }

        return $this->name;
    }


    /**
     * Setter for the shipping address
     *
     * @param \Heidelpay\PhpPaymentApi\ParameterGroups\AddressParameterGroup $address
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\ShippingParameterGroup
     */
    public function setAddress(AddressParameterGroup $address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * Setter for the shipping name
     *
     * @param \Heidelpay\PhpPaymentApi\ParameterGroups\NameParameterGroup $name
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\ShippingParameterGroup
     */
    public function setName(NameParameterGroup $name)
    {
        $this->name = $name;
        return $this;
    }
}
