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
    public $fields = [
        'ReversalType' => 'reversaltype'
    ];

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

    /**
     * Only map fields that are listed here.
     *
     * @inheritdoc
     */
    public function getXmlObjectField(\SimpleXMLElement $xmlElement, $field)
    {
        if (isset($xmlElement->Transaction->Payment, $field)) {
            $xmlField = $xmlElement->Transaction->Payment->$field;
            if (array_key_exists($xmlField->getName(), $this->fields)) {
                return (string)$xmlField;
            }
        }
        return null;
    }
}
