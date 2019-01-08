<?php
/**
 * Created by PhpStorm.
 * User: David.Owusu
 * Date: 12.12.2018
 * Time: 16:36
 */
namespace Heidelpay\PhpPaymentApi\PushMapping;

class Home extends AbstractPushMapper
{
    public $fields = [
        'City' => 'city',
        'Country' => 'country',
        'Street' => 'street',
        'Zip' => 'zip',
    ];

    public function getXmlObjectField(\SimpleXMLElement $xmlElement, $field)
    {
        if (isset($xmlElement->Home, $xmlElement->Home->$field)) {
            return (string) $xmlElement->Home->$field;
        }

        return null;
    }
}

