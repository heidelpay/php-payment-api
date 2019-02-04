<?php

namespace Heidelpay\PhpPaymentApi\PushMapping;

/**
 * XML Push Mapping Class for the Home Parameter Group
 *
 * @license    Use of this software requires acceptance of the License Agreement. See LICENSE file.
 * @copyright  Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link       http://dev.heidelpay.com/php-payment-api
 *
 * @author     David Owusu
 *
 * @package heidelpay\php-payment-api\push-mapping
 */
class Home extends AbstractPushMapper
{
    /**
     * @inheritdoc
     */
    public $fields = [
        'City' => 'city',
        'Country' => 'country',
        'Street' => 'street',
        'Zip' => 'zip',
    ];

    /**
     * @inheritdoc
     */
    public function getXmlObjectField(\SimpleXMLElement $xmlElement, $field)
    {
        if (isset($xmlElement->Home, $xmlElement->Home->$field)) {
            return (string) $xmlElement->Home->$field;
        }

        return null;
    }
}
