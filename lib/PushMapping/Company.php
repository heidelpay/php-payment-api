<?php
/**
 * Created by PhpStorm.
 * User: David.Owusu
 * Date: 27.11.2018
 * Time: 11:48
 */
namespace Heidelpay\PhpPaymentApi\PushMapping;

class Company extends AbstractPushMapper
{
    public $fields = [
        'CompanyName' => 'companyname',
        'RegistrationType' => 'registrationtype',
        'CommercialRegisterNumber' => 'commercialregisternumber',
        'VatID' => 'vatid',
        //'Executive' => 'executive',
        'CommercialSector' => 'commercialsector',
        //'Location' => 'location',
    ];

    /**
     * @inheritdoc
     */
    public function getXmlObjectField(\SimpleXMLElement $xmlElement, $field)
    {
        if (isset($xmlElement->Transaction, $xmlElement->Transaction->Customer->Company->$field)) {
            return (string)$xmlElement->Transaction->Customer->Company->$field;
        }

        return null;
    }
}
