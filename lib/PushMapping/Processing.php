<?php

namespace Heidelpay\PhpApi\PushMapping;

/**
 * Summary
 *
 * @license Use of this software requires acceptance of the License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link https://dev.heidelpay.de/php-api
 *
 * @author Stephano Vogel
 *
 * @package heidelpay
 * @subpackage php-api
 * @category php-api
 */
class Processing extends AbstractPushMapper
{
    public $fields = [
        'ConfirmationStatus' => 'confirmation_status',
        'Reason' => 'reason',
        'Result' => 'result',
        'Return' => 'return',
        'Status' => 'status',
        'Timestamp' => 'timestamp',
    ];

    public $fieldAttributes = [
        'Reason:code' => 'reason_code',
        'Return:code' => 'return_code',
        'Status:code' => 'status_code',
    ];

    public $properties = [
        'code' => 'code'
    ];

    public function getXmlObjectField(\SimpleXMLElement $xmlElement, $field)
    {
        if (isset($xmlElement->Transaction->Processing->$field)) {
            return (string)$xmlElement->Transaction->Processing->$field;
        }

        return null;
    }

    public function getXmlObjectFieldAttribute(\SimpleXMLElement $xmlElement, $fieldAttribute)
    {
        list($field, $attribute) = explode(':', $fieldAttribute);

        if (isset($xmlElement->Transaction->Processing->$field)) {
            if (isset($xmlElement->Transaction->Processing->$field->attributes()->$attribute)) {
                return (string)$xmlElement->Transaction->Processing->$field->attributes()->$attribute;
            }
        }

        return null;
    }

    public function getXmlObjectProperty(\SimpleXMLElement $xmlElement, $property)
    {
        if (isset($xmlElement->Transaction->Processing[$property])) {
            return (string)$xmlElement->Transaction->Processing[$property];
        }

        return null;
    }
}
