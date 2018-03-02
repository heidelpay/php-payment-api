<?php

namespace Heidelpay\PhpPaymentApi\PushMapping;

/**
 * XML Push Mapping Class for the Name Parameter Group
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
class Name extends AbstractPushMapper
{
    /**
     * @inheritdoc
     */
    public $fields = [
        'Birthdate' => 'birthdate',
        'Company' => 'company',
        'Family' => 'family',
        'Given' => 'given',
        'Salutation' => 'salutation',
        'Title' => 'title',
    ];

    /**
     * @inheritdoc
     */
    public function getXmlObjectField(\SimpleXMLElement $xmlElement, $field)
    {
        if (isset($xmlElement->Transaction, $xmlElement->Transaction->Customer->Name->$field)) {
            return (string)$xmlElement->Transaction->Customer->Name->$field;
        }

        return null;
    }
}
