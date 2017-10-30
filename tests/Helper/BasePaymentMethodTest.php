<?php
/**
 * Created by PhpStorm.
 * User: Simon.Gabriel
 * Date: 24.10.2017
 * Time: 12:19
 */
namespace Heidelpay\Tests\PhpApi\Helper;

use AspectMock\Proxy\InstanceProxy;
use Codeception\TestCase\Test;
use Heidelpay\PhpApi\Adapter\CurlAdapter;
use AspectMock\Test as AspectMockTest;
use Heidelpay\PhpApi\Response;

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
     * PaymentObject
     *
     * @var \Heidelpay\PhpApi\PaymentMethods\CreditCardPaymentMethod
     */
    protected $paymentObject;

    /**
     * BasePaymentMethodTest constructor.
     *
     * @param null   $name
     * @param array  $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        date_default_timezone_set('UTC');

        $this->authentication = new Authentication();
        $this->customerData = new Customer();

        parent::__construct($name, $data, $dataName);
    }

    //<editor-fold desc="Helpers">

    /**
     * Get currently called method, without namespace
     *
     * @param string $method
     *
     * @return string class and method
     */
    protected function getMethod($method)
    {
        return substr(strrchr($method, '\\'), 1);
    }

    /**
     * @return InstanceProxy|CurlAdapter
     */
    protected function mockCurlAdapter()
    {
        /** @var CurlAdapter|InstanceProxy $curlMock */
        $curlMock =  AspectMockTest::double(
            new CurlAdapter,
            ['sendPost' => [[], new Response()]]
        );

        $this->paymentObject->setAdapter($curlMock);

        return $curlMock;
    }

    /**
     * @return string
     */
    protected function getTimestampString()
    {
        return $this->getMethod(__METHOD__) . ' ' . date('Y-m-d H:i:s');
    }

    //</editor-fold>
}
