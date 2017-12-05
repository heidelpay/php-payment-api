<?php

namespace Heidelpay\PhpPaymentApi\PushMapping;

/**
 * XML Push Mapping Class for Account Parameter Group
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
class Account extends AbstractPushMapper
{
    public $fields = [
        'Bank' => 'bank',
        'BankName' => 'bankname',
        'Brand' => 'brand',
        'Bic' => 'bic',
        'Country' => 'country',
        'Holder' => 'holder',
        'Iban' => 'iban',
        'Identification' => 'identification',
        'Number' => 'number',
    ];

    public $fieldAttributes = [
        'Expiry:year' => 'expiry_year',
        'Expiry:month' => 'expiry_month',
    ];

    public function getXmlObjectField(\SimpleXMLElement $xmlElement, $field)
    {
        if (isset($xmlElement->Transaction->Account->$field)) {
            return (string)$xmlElement->Transaction->Account->$field;
        }

        return null;
    }

    public function getXmlObjectFieldAttribute(\SimpleXMLElement $xmlElement, $fieldAttribute)
    {
        list($field, $attribute) = explode(':', $fieldAttribute);

        if (isset($xmlElement->Transaction->Account->$field)) {
            if (isset($xmlElement->Transaction->Account->$field->attributes()->$attribute)) {
                return (string)$xmlElement->Transaction->Account->$field->attributes()->$attribute;
            }
        }

        return null;
    }
}
