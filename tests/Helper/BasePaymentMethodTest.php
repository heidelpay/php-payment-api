<?php
/**
 * Created by PhpStorm.
 * User: Simon.Gabriel
 * Date: 24.10.2017
 * Time: 12:19
 */
namespace Heidelpay\Tests\PhpPaymentApi\Helper;

use AspectMock\Proxy\InstanceProxy;
use Codeception\Lib\Console\Output;
use Codeception\Test\Unit;
use Heidelpay\PhpPaymentApi\Adapter\CurlAdapter;
use AspectMock\Test as test;
use Heidelpay\PhpPaymentApi\Adapter\HttpAdapterInterface;
use Heidelpay\PhpPaymentApi\PaymentMethods\CreditCardPaymentMethod;
use Heidelpay\PhpPaymentApi\PaymentMethods\DebitCardPaymentMethod;
use Heidelpay\PhpPaymentApi\PaymentMethods\DirectDebitB2CSecuredPaymentMethod;
use Heidelpay\PhpPaymentApi\PaymentMethods\DirectDebitPaymentMethod;
use Heidelpay\PhpPaymentApi\PaymentMethods\EasyCreditPaymentMethod;
use Heidelpay\PhpPaymentApi\PaymentMethods\EPSPaymentMethod;
use Heidelpay\PhpPaymentApi\PaymentMethods\GiropayPaymentMethod;
use Heidelpay\PhpPaymentApi\PaymentMethods\IDealPaymentMethod;
use Heidelpay\PhpPaymentApi\PaymentMethods\InvoiceB2CSecuredPaymentMethod;
use Heidelpay\PhpPaymentApi\PaymentMethods\InvoicePaymentMethod;
use Heidelpay\PhpPaymentApi\PaymentMethods\PayPalPaymentMethod;
use Heidelpay\PhpPaymentApi\PaymentMethods\PostFinanceCardPaymentMethod;
use Heidelpay\PhpPaymentApi\PaymentMethods\PostFinanceEFinancePaymentMethod;
use Heidelpay\PhpPaymentApi\PaymentMethods\PrepaymentPaymentMethod;
use Heidelpay\PhpPaymentApi\PaymentMethods\Przelewy24PaymentMethod;
use Heidelpay\PhpPaymentApi\PaymentMethods\SantanderInvoicePaymentMethod;
use Heidelpay\PhpPaymentApi\PaymentMethods\SofortPaymentMethod;
use Heidelpay\PhpPaymentApi\Response;
use Heidelpay\Tests\PhpPaymentApi\Helper\Constraints\ArraysMatchConstraint;
use PHPUnit\Framework\Constraint\Constraint;

/**
 * Base test class for unit and integration tests.
 */
class BasePaymentMethodTest extends Unit
{
    const REFERENCE_ID = 'http://www.heidelpay.com';
    const REDIRECT_URL = 'http://dev.heidelpay.com';
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
     * @var InstanceProxy $adapterMock
     */
    private $adapterMock;

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
     * @return InstanceProxy
     */
    public function getAdapterMock()
    {
        return $this->adapterMock;
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
     * @return InstanceProxy|HttpAdapterInterface
     *
     * @throws \Exception
     */
    protected function mockCurlAdapter()
    {
        /** @var HttpAdapterInterface|InstanceProxy $curlMock */
        $curlMock =  test::double(
            new CurlAdapter,
            ['sendPost' => [[], new Response()]]
        );

        $this->paymentObject->setAdapter($curlMock->getObject());

        $this->adapterMock = $curlMock;

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

    /**
     * Print debug data to codecept console (codecept run integration --debug)
     * Pass result data if send has been called manually.
     *
     * @param mixed $result
     */
    protected function logDataToDebug($result = null)
    {
        $result = $result ?: $this->paymentObject->getResponseArray();
        codecept_debug("\nrequest: " . print_r($this->paymentObject->getRequest()->toArray(), 1));
        if (!empty($result)) {
            codecept_debug('response: ' . print_r($result, 1));
        }
    }

    //</editor-fold>
}
