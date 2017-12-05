<?php

namespace Heidelpay\PhpPaymentApi\PushMapping;

/**
 * Summary
 *
 * @license    Use of this software requires acceptance of the License Agreement. See LICENSE file.
 * @copyright  Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link       https://dev.heidelpay.de/php-api
 *
 * @author     Stephano Vogel
 *
 * @package    heidelpay
 * @subpackage php-api
 * @category   php-api
 */
class Connector extends AbstractPushMapper
{
    public $fields = [
        'Bank' => 'account_bank',
        'Bic' => 'account_bic',
        'Country' => 'account_country',
        'Holder' => 'account_holder',
        'Iban' => 'account_iban',
        'Number' => 'account_number',
        'Usage' => 'account_usage',
    ];

    public function getXmlObjectField(\SimpleXMLElement $xmlElement, $field)
    {
        if (isset($xmlElement->Transaction->Connector->Account->$field)) {
            return (string) $xmlElement->Transaction->Connector->Account->$field;
        }

        return null;
    }
}
