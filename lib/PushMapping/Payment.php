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
class Payment extends AbstractPushMapper
{
    public $properties = [
        'code' => 'code',
    ];

    public function getXmlObjectProperty(\SimpleXMLElement $xmlElement, $property)
    {
        if (isset($xmlElement->Transaction->Payment[$property])) {
            return (string)$xmlElement->Transaction->Payment[$property];
        }

        return null;
    }
}
