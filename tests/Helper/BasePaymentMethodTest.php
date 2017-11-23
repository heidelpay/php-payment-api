<?php
/**
 * Created by PhpStorm.
 * User: Simon.Gabriel
 * Date: 24.10.2017
 * Time: 12:19
 */
namespace Heidelpay\Tests\PhpApi\Helper;

use AspectMock\Proxy\InstanceProxy;
use Codeception\Lib\Console\Output;
use Codeception\Test\Unit;
use Heidelpay\PhpApi\Adapter\CurlAdapter;
use AspectMock\Test as test;
use Heidelpay\PhpApi\PaymentMethods\CreditCardPaymentMethod;
use Heidelpay\PhpApi\PaymentMethods\DebitCardPaymentMethod;
use Heidelpay\PhpApi\PaymentMethods\DirectDebitB2CSecuredPaymentMethod;
use Heidelpay\PhpApi\PaymentMethods\DirectDebitPaymentMethod;
use Heidelpay\PhpApi\PaymentMethods\EasyCreditPaymentMethod;
use Heidelpay\PhpApi\PaymentMethods\EPSPaymentMethod;
use Heidelpay\PhpApi\PaymentMethods\GiropayPaymentMethod;
use Heidelpay\PhpApi\PaymentMethods\IDealPaymentMethod;
use Heidelpay\PhpApi\PaymentMethods\InvoiceB2CSecuredPaymentMethod;
use Heidelpay\PhpApi\PaymentMethods\InvoicePaymentMethod;
use Heidelpay\PhpApi\PaymentMethods\PayPalPaymentMethod;
use Heidelpay\PhpApi\PaymentMethods\PostFinanceCardPaymentMethod;
use Heidelpay\PhpApi\PaymentMethods\PostFinanceEFinancePaymentMethod;
use Heidelpay\PhpApi\PaymentMethods\PrepaymentPaymentMethod;
use Heidelpay\PhpApi\PaymentMethods\Przelewy24PaymentMethod;
use Heidelpay\PhpApi\PaymentMethods\SantanderInvoicePaymentMethod;
use Heidelpay\PhpApi\PaymentMethods\SofortPaymentMethod;
use Heidelpay\PhpApi\Response;
use Heidelpay\Tests\PhpApi\Helper\Constraints\ArraysMatchConstraint;
use PHPUnit\Framework\Constraint\Constraint;

/**
 * Base test class for unit and integration tests.
 */
class BasePaymentMethodTest extends Unit
{
    const REFERENCE_ID = 'http://www.heidelpay.de';
    const REDIRECT_URL = 'https://dev.heidelpay.de';
    const RESPONSE_URL = self::REDIRECT_URL . '/response';
    const PAYMENT_FRAME_ORIGIN = self::REFERENCE_ID;
    const CSS_PATH = self::REFERENCE_ID;
    const TEST_AMOUNT = 23.12;
    const NAME_COMPANY = 'DevHeidelpay';

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
     * @var CreditCardPaymentMethod|DebitCardPaymentMethod|DirectDebitB2CSecuredPaymentMethod|DirectDebitPaymentMethod|EasyCreditPaymentMethod|EPSPaymentMethod|GiropayPaymentMethod|IDealPaymentMethod|InvoiceB2CSecuredPaymentMethod|InvoicePaymentMethod|PayPalPaymentMethod|PostFinanceCardPaymentMethod|PostFinanceEFinancePaymentMethod|PrepaymentPaymentMethod|Przelewy24PaymentMethod|SantanderInvoicePaymentMethod|SofortPaymentMethod
     */
    protected $paymentObject;

    /**
     * @var Output $logger
     */
    private $logger;

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

    /**
     * @return Output
     */
    public function getLogger()
    {
        if (!($this->logger instanceof Output)) {
            $this->logger = new Output([]);
        }

        return $this->logger;
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
        $curlMock =  test::double(
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

    /**
     * @param $parameters
     *
     * @return ArraysMatchConstraint|Constraint
     */
    protected function arraysMatchExactly($parameters)
    {
        return new ArraysMatchConstraint($parameters, true, true);
    }

    /**
     * @return mixed
     */
    protected function getPaymentObject()
    {
        return $this->paymentObject;
    }

    /**
     * Writes a message to the console.
     *
     * @param $message
     */
    protected function log($message)
    {
        $this->getLogger()->write($message);
    }

    protected function success()
    {
        $output = $this->getLogger();
        $output->writeln('<ok> success</ok>');
    }

    //</editor-fold>
}
