<?php

namespace Heidelpay\PhpPaymentApi\PushMapping;

/**
 * XML Push Mapping Class for the Payment Parameter Group
 *
 * @license Use of this software requires acceptance of the License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link http://dev.heidelpay.com/php-payment-api
 *
 * @author Stephano Vogel
 *
 * @package heidelpay\php-payment-api\push-mapping
 */
class Payment extends AbstractPushMapper
{
    /**
     * @inheritdoc
     */
    public $properties = [
        'code' => 'code',
    ];

    /**
     * @inheritdoc
     */
    public function getXmlObjectProperty(\SimpleXMLElement $xmlElement, $property)
    {
        if (isset($xmlElement->Transaction, $xmlElement->Transaction->Payment[$property])) {
            return (string)$xmlElement->Transaction->Payment[$property];
        }

        return null;
    }
}
