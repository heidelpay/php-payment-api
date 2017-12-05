<?php

namespace Heidelpay\PhpPaymentApi\PushMapping;

/**
 * XML Push Mapping Class for Name Parameter Group
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
class Name extends AbstractPushMapper
{
    public $fields = [
        'Birthdate' => 'birthdate',
        'Company' => 'company',
        'Family' => 'family',
        'Given' => 'given',
        'Salutation' => 'salutation',
        'Title' => 'title',
    ];

    public function getXmlObjectField(\SimpleXMLElement $xmlElement, $field)
    {
        if (isset($xmlElement->Transaction->Customer->Name->$field)) {
            return (string)$xmlElement->Transaction->Customer->Name->$field;
        }

        return null;
    }
}
