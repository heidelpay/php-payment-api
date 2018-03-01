<?php

namespace Heidelpay\PhpPaymentApi\PushMapping;

/**
 * XML Push Mapping Class for the Contact Parameter Group
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
class Contact extends AbstractPushMapper
{
    /**
     * @inheritdoc
     */
    public $fields = [
        'Email' => 'email',
        'Ip' => 'ip',
        'Mobile' => 'mobile',
        'Phone' => 'phone',
    ];

    /**
     * @inheritdoc
     */
    public function getXmlObjectField(\SimpleXMLElement $xmlElement, $field)
    {
        if (isset($xmlElement->Transaction, $xmlElement->Transaction->Customer->Contact->$field)) {
            return (string)$xmlElement->Transaction->Customer->Contact->$field;
        }

        return null;
    }
}
