<?php

namespace Heidelpay\PhpPaymentApi\PushMapping;

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
class Presentation extends AbstractPushMapper
{
    public $fields = [
        'Amount' => 'amount',
        'Currency' => 'currency',
        'Usage' => 'usage',
    ];

    public function getXmlObjectField(\SimpleXMLElement $xmlElement, $field)
    {
        if (isset($xmlElement->Transaction->Payment->Presentation->$field)) {
            return (string)$xmlElement->Transaction->Payment->Presentation->$field;
        }

        return null;
    }
}
