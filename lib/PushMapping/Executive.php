<?php
/**
 * Created by PhpStorm.
 * User: David.Owusu
 * Date: 08.01.2019
 * Time: 14:58
 */

namespace Heidelpay\PhpPaymentApi\PushMapping;


class Executive extends AbstractPushMapper
{
    public $fields = [
        'Given' => 'given',
        'Family' => 'family',
        'Birthdate' => 'birthdate',
        'Phone' => 'phone',
        //'Executive' => 'executive',
        'Email' => 'email',
        'Function' => 'function',
        //'Location' => 'location',
    ];

    /**
     * @inheritdoc
     */
    public function getXmlObjectField(\SimpleXMLElement $xmlElement, $field)
    {
        if (isset( $xmlElement->$field)) {
            return (string)$xmlElement->$field;
        }

        return null;
    }
}