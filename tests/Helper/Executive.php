<?php
/**
 * Created by PhpStorm.
 * User: David.Owusu
 * Date: 14.01.2019
 * Time: 09:34
 */

namespace Heidelpay\Tests\PhpPaymentApi\Helper;


use Heidelpay\PhpPaymentApi\ParameterGroups\HomeParameterGroup;

class Executive
{

    public function __construct()
    {
        $homeDataArray = [
            'Vangerowstr. 18',
            '69115',
            'Heidelberg',
            'DE',
        ];
        $home = new HomeParameterGroup(...$homeDataArray);

        $this->executiveOneArray = [
            'OWNER',
            null,
            'Testkäufer',
            'Händler',
            '1988-12-12',
            'example@email.de',
            '062216471400',
            $home
        ];

        $this->executiveTwoArray = [
            'OWNER',
            null,
            null,
            null,
            '123',
            null,
            null,
            null
        ];
    }

    protected $executiveOneArray;

    /**
     * @return array
     */
    public function getExecutiveOneArray()
    {
        return $this->executiveOneArray;
    }

    /**
     * @return array
     */
    public function getExecutiveTwoArray()
    {
        return $this->executiveTwoArray;
    }

    protected $executiveTwoArray;
}