<?php

namespace Heidelpay\Tests\PhpPaymentApi\Helper;

class Executive
{
    public function __construct()
    {
        $this->executiveOneArray = [
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
            'OWNER',
        ];

        $this->executiveTwoArray = [
            '',
            '',
            '',
            '123',
            '',
            '',
            'null',
            'null',
            'null',
            'null',
            'OWNER',
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
