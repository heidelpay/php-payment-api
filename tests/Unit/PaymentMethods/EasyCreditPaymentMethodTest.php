<?php

namespace Heidelpay\Tests\PhpApi\Unit\PaymentMethods;

use Heidelpay\PhpApi\PaymentMethods\EasyCreditPaymentMethod;
use PHPUnit\Framework\TestCase;

/**
 * Desc
 * @license Use of this software requires acceptance of the License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 * @link https://dev.heidelpay.de/php-api
 * @author Stephano Vogel
 * @package heidelpay
 * @subpackage php-api
 * @category php-api
 */
class EasyCreditPaymentMethodTest extends TestCase
{
    /**
     * @var array authentification parameter for heidelpay api
     */
    protected $authentification = array(
        '31HA07BC8142C5A171745D00AD63D182', //SecuritySender
        '31ha07bc8142c5a171744e5aef11ffd3', //UserLogin
        '93167DE7', //UserPassword
        '31HA07BC81856CAD6D8E07858ACD6CFB', //TransactionChannel
        true //Sandbox mode
    );

    /**
     * @var array customer address
     */
    protected $customerDetails = array(
        'Heidel', //NameGiven
        'Berger-Payment', //NameFamily
        null, //NameCompany
        '1234', //IdentificationShopperId
        'Vagerowstr. 18', //AddressStreet
        'DE-BW', //AddressState
        '69115', //AddressZip
        'Heidelberg', //AddressCity
        'DE', //AddressCountry
        'development@heidelpay.de' //Costumer
    );

    /**
     * Transaction currency
     *
     * @var string currency
     */
    protected $currency = 'EUR';
    /**
     * Secret
     *
     * The secret will be used to generate a hash using
     * transaction id + secret. This hash can be used to
     * verify the the payment response. Can be used for
     * brute force protection.
     *
     * @var string secret
     */
    protected $secret = 'Heidelpay-PhpApi';

    /**
     * PaymentObject
     *
     * @var \Heidelpay\PhpApi\PaymentMethods\InvoiceB2CSecuredPaymentMethod
     */
    protected $paymentObject = null;

    /**
     * Constructor used to set timezone to utc
     */
    public function __construct()
    {
        date_default_timezone_set('UTC');
        parent::__construct();
    }

    /**
     * Set up function will create a invoice object for each testcase
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
        $easyCredit = new EasyCreditPaymentMethod();

        $easyCredit->getRequest()->authentification(...$this->authentification);
        $easyCredit->getRequest()->customerAddress(...$this->customerDetails);

        $this->paymentObject = $easyCredit;
    }
}
