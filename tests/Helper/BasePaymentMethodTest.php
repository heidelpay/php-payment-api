<?php
/**
 * Created by PhpStorm.
 * User: Simon.Gabriel
 * Date: 24.10.2017
 * Time: 12:19
 */
namespace Heidelpay\Tests\PhpApi\Helper;

use Codeception\TestCase\Test;

class BasePaymentMethodTest extends Test
{
    /**
     * Authentication data for heidelpay api
     *
     * @var Authentication $authentication
     */
    protected $authentication;

    /**
     * Customer data for heidelpay api
     *
     * @var Customer $customerData
     */
    protected $customerData;

    /**
     * BasePaymentMethodTest constructor.
     */
    public function __construct()
    {
        $this->authentication = new Authentication();
        $this->customerData = new Customer();

        parent::__construct();
    }
}
