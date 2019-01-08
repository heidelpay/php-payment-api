<?php
/**
 * Created by PhpStorm.
 * User: David.Owusu
 * Date: 12.12.2018
 * Time: 16:36
 */
namespace Heidelpay\PhpPaymentApi\PushMapping;

class Location extends AbstractPushMapper
{
    public $fields = [
        'City' => 'city',
        'RegistrationType' => 'registrationtype',
        'Country' => 'country',
        'Street' => 'street',
        'Zip' => 'zip',
    ];

    public function getXmlObjectField(\SimpleXMLElement $xmlElement, $field)
    {
        if (isset($xmlElement->Transaction, $xmlElement->Transaction->Customer->Company->Location->$field)) {
            return (string) $xmlElement->Transaction->Customer->Company->Location->$field;
        }

        return null;
    }
}

