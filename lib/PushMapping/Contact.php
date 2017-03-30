<?php

namespace Heidelpay\PhpApi\PushMapping;

/**
 * Summary
 *
 * Desc
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
class Contact extends AbstractPushMapper
{
    public $fields = [
        'Email' => 'email',
        'Ip' => 'ip',
        'Mobile' => 'mobile',
        'Phone' => 'phone',
    ];

    public function getXmlObjectField(\SimpleXMLElement $xmlElement, $field)
    {
        if (isset($xmlElement->Transaction->Customer->Contact->$field)) {
            return (string)$xmlElement->Transaction->Customer->Contact->$field;
        }

        return null;
    }
}
