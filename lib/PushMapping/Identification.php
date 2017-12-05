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
class Identification extends AbstractPushMapper
{
    public $fields = [
        'CreditorID' => 'creditor_id',
        'ReferenceID' => 'referenceid',
        'ShopperID' => 'shopperid',
        'ShortID' => 'shortid',
        'TransactionID' => 'transactionid',
        'UniqueID' => 'uniqueid',
    ];

    public function getXmlObjectField(\SimpleXMLElement $xmlElement, $field)
    {
        if (isset($xmlElement->Transaction->Identification->$field)) {
            return (string)$xmlElement->Transaction->Identification->$field;
        }

        return null;
    }
}
