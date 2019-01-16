<?php

namespace Heidelpay\PhpPaymentApi\PushMapping;

/**
 * XML Push Mapping Class for the Executive Parameter Group
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
class Executive extends AbstractPushMapper
{
    /**
     * @inheritdoc
     */
    public $fields = [
        'Given' => 'given',
        'Family' => 'family',
        'Birthdate' => 'birthdate',
        'Phone' => 'phone',
        'Email' => 'email',
        'Function' => 'function',
    ];

    /**
     * @inheritdoc
     */
    public function getXmlObjectField(\SimpleXMLElement $xmlElement, $field)
    {
        if (isset($xmlElement->$field)) {
            return (string)$xmlElement->$field;
        }

        return null;
    }
}
