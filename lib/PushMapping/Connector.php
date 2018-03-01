<?php

namespace Heidelpay\PhpPaymentApi\PushMapping;

/**
 * XML Push Mapping Class for the Connector Parameter Group
 *
 * @license    Use of this software requires acceptance of the License Agreement. See LICENSE file.
 * @copyright  Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link       http://dev.heidelpay.com/php-payment-api
 *
 * @author     Stephano Vogel
 *
 * @package heidelpay\php-payment-api\push-mapping
 */
class Connector extends AbstractPushMapper
{
    /**
     * @inheritdoc
     */
    public $fields = [
        'Bank' => 'account_bank',
        'Bic' => 'account_bic',
        'Country' => 'account_country',
        'Holder' => 'account_holder',
        'Iban' => 'account_iban',
        'Number' => 'account_number',
        'Usage' => 'account_usage',
    ];

    /**
     * @inheritdoc
     */
    public function getXmlObjectField(\SimpleXMLElement $xmlElement, $field)
    {
        if (isset($xmlElement->Transaction, $xmlElement->Transaction->Connector->Account->$field)) {
            return (string) $xmlElement->Transaction->Connector->Account->$field;
        }

        return null;
    }
}
