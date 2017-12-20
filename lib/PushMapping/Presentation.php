<?php

namespace Heidelpay\PhpPaymentApi\PushMapping;

/**
 * XML Push Mapping Class for the Presentation Parameter Group
 *
 * @license Use of this software requires acceptance of the License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link https://dev.heidelpay.de/php-api
 *
 * @author Stephano Vogel
 *
 * @package heidelpay\php-payment-api\push-mapping
 */
class Presentation extends AbstractPushMapper
{
    /**
     * @inheritdoc
     */
    public $fields = [
        'Amount' => 'amount',
        'Currency' => 'currency',
        'Usage' => 'usage',
    ];

    /**
     * @inheritdoc
     */
    public function getXmlObjectField(\SimpleXMLElement $xmlElement, $field)
    {
        if (isset($xmlElement->Transaction, $xmlElement->Transaction->Payment->Presentation->$field)) {
            return (string)$xmlElement->Transaction->Payment->Presentation->$field;
        }

        return null;
    }
}
