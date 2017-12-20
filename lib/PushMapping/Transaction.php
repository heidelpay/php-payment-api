<?php

namespace Heidelpay\PhpPaymentApi\PushMapping;

/**
 * XML Push Mapping Class for the Transaction Parameter Group
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
class Transaction extends AbstractPushMapper
{
    /**
     * @inheritdoc
     */
    public $properties = [
        'channel' => 'channel',
        'mode' => 'mode',
    ];

    /**
     * @inheritdoc
     */
    public function getXmlObjectProperty(\SimpleXMLElement $xmlElement, $property)
    {
        if (isset($xmlElement->Transaction[$property])) {
            return (string)$xmlElement->Transaction[$property];
        }

        return null;
    }
}
