<?php

namespace Heidelpay\PhpPaymentApi\ParameterGroups;

/**
 * This class provides every api parameter related to the basket data
 *
 * The only parameter of the basket group is used to connect a basket, sent via Basket API with a
 * payment transaction. Please refer to the documentation “heidelpayIntegrationGuideBasket-API_(en)”
 * for more information on how to use the basket API.
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
class BasketParameterGroup extends AbstractParameterGroup
{
    /**
     * Basket id
     *
     * ID of the basket that was transmitted
     * via the basket API to the payment system.
     *
     * @var string reference id of a transmitted basket (optional)
     */
    public $id;

    /**
     * BasketId getter
     *
     * @return string id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Setter for the basket id
     *
     * @param string $id
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\BasketParameterGroup
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
}
