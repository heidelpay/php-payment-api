<?php
/**
 * Created by PhpStorm.
 * User: David.Owusu
 * Date: 07.12.2018
 * Time: 18:24
 */
namespace Heidelpay\PhpPaymentApi\ParameterGroups;

class HomeParameterGroup extends AbstractParameterGroup
{
    public $street;

    public $zip;

    public $city;

    public $country;
}
