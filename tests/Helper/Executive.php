<?php
/**
 * Created by PhpStorm.
 * User: David.Owusu
 * Date: 14.01.2019
 * Time: 09:34
 */
namespace Heidelpay\Tests\PhpPaymentApi\Helper;

class Executive
{
    public function __construct()
    {
        $this->executiveOneArray = [
            'OWNER',
            null,
            'Testkäufer',
            'Händler',
            '1988-12-12',
            'example@email.de',
            '062216471400',
            'Vangerowstr. 18',
            '69115',
            'Heidelberg',
            'DE',
        ];

        $this->executiveTwoArray = [
            'OWNER',
            '',
            '',
            '',
            '123',
            '',
            '',
            'null',
            'null',
            'null',
            'null'
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
