<?php

namespace Heidelpay\PhpPaymentApi\PushMapping;

/**
 * XML Push Mapping Class for the Processing Parameter Group
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
class Processing extends AbstractPushMapper
{
    /**
     * @inheritdoc
     */
    public $fields = [
        'ConfirmationStatus' => 'confirmation_status',
        'Reason' => 'reason',
        'Result' => 'result',
        'Return' => 'return',
        'Status' => 'status',
        'Timestamp' => 'timestamp',
    ];

    /**
     * @inheritdoc
     */
    public $fieldAttributes = [
        'Reason:code' => 'reason_code',
        'Return:code' => 'return_code',
        'Status:code' => 'status_code',
    ];

    /**
     * @inheritdoc
     */
    public $properties = [
        'code' => 'code'
    ];

    /**
     * @inheritdoc
     */
    public function getXmlObjectField(\SimpleXMLElement $xmlElement, $field)
    {
        if (isset($xmlElement->Transaction, $xmlElement->Transaction->Processing->$field)) {
            return (string)$xmlElement->Transaction->Processing->$field;
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function getXmlObjectFieldAttribute(\SimpleXMLElement $xmlElement, $fieldAttribute)
    {
        list($field, $attribute) = explode(':', $fieldAttribute);

        if (isset($xmlElement->Transaction, $xmlElement->Transaction->Processing->$field,
            $xmlElement->Transaction->Processing->$field->attributes()->$attribute)
        ) {
            return (string)$xmlElement->Transaction->Processing->$field->attributes()->$attribute;
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function getXmlObjectProperty(\SimpleXMLElement $xmlElement, $property)
    {
        if (isset($xmlElement->Transaction, $xmlElement->Transaction->Processing[$property])) {
            return (string)$xmlElement->Transaction->Processing[$property];
        }

        return null;
    }
}
