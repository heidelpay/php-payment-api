<?php

namespace Heidelpay\PhpPaymentApi\PushMapping;

/**
 * XML Push Mapping Class for the Address Parameter Group
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
class Address extends AbstractPushMapper
{
    /**
     * @inheritdoc
     */
    public $fields = [
        'City' => 'city',
        'Country' => 'country',
        'State' => 'state',
        'Street' => 'street',
        'Zip' => 'zip',
    ];

    /**
     * @inheritdoc
     */
    public function getXmlObjectField(\SimpleXMLElement $xmlElement, $field)
    {
        if (isset($xmlElement->Transaction, $xmlElement->Transaction->Customer->Address->$field)) {
            return (string) $xmlElement->Transaction->Customer->Address->$field;
        }

        return null;
    }
}
