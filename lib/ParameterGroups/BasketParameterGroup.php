<?php
namespace Heidelpay\PhpApi\ParameterGroups;

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
 * @link  https://dev.heidelpay.de/PhpApi
 *
 * @author  Jens Richter
 *
 * @package  Heidelpay
 * @subpackage PhpApi
 * @category PhpApi
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
    public $id = null;

    /**
     * BasketId getter
     *
     * @return string id
     */
    public function getId()
    {
        return $this->id;
    }
}
