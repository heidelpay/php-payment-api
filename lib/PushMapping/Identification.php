<?php

namespace Heidelpay\PhpPaymentApi\PushMapping;

/**
 * XML Push Mapping Class for the Identification Parameter Group
 *
 * @license Use of this software requires acceptance of the License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link http://dev.heidelpay.com/php-payment-api
 *
 * @author Stephano Vogel
 *
 * @package heidelpay\php-payment-api\push-mapping
 */
class Identification extends AbstractPushMapper
{
    /**
     * @inheritdoc
     */
    public $fields = [
        'CreditorID' => 'creditor_id',
        'ReferenceID' => 'referenceid',
        'ShopperID' => 'shopperid',
        'ShortID' => 'shortid',
        'TransactionID' => 'transactionid',
        'UniqueID' => 'uniqueid',
    ];

    /**
     * @inheritdoc
     */
    public function getXmlObjectField(\SimpleXMLElement $xmlElement, $field)
    {
        if (isset($xmlElement->Transaction, $xmlElement->Transaction->Identification->$field)) {
            return (string)$xmlElement->Transaction->Identification->$field;
        }

        return null;
    }
}
