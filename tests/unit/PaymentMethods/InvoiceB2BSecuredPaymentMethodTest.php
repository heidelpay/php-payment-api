<?php
/**
 * Created by PhpStorm.
 * User: David.Owusu
 * Date: 28.11.2018
 * Time: 13:12
 */
namespace Heidelpay\Tests\PhpPaymentApi\unit\PaymentMethods;

use AspectMock\Test as test;
use Heidelpay\PhpPaymentApi\Constants\CommercialSector;
use Heidelpay\PhpPaymentApi\Constants\RegistrationType;
use Heidelpay\PhpPaymentApi\ParameterGroups\HomeParameterGroup;
use Heidelpay\PhpPaymentApi\ParameterGroups\LocationParameterGroup;
use Heidelpay\PhpPaymentApi\PaymentMethods\InvoiceB2CSecuredPaymentMethod;
use Heidelpay\PhpPaymentApi\Request;
use Heidelpay\Tests\PhpPaymentApi\Helper\BasePaymentMethodTest;
use Heidelpay\Tests\PhpPaymentApi\Helper\Company;

class InvoiceB2BSecuredPaymentMethodTest extends BasePaymentMethodTest
{
    /**
     * @var Company
     */
    protected $company;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->company = new Company();
    }
    /**
     * Set up function will create a payment method object for each test case
     *
     * @throws \Exception
     */
    // @codingStandardsIgnoreStart
    public function _before()
    {
        // @codingStandardsIgnoreEnd
        $this->authentication->setTransactionChannel('31HA07BC8142C5A171744F3D6D155865');

        $paymentObject = new InvoiceB2CSecuredPaymentMethod();

        $request = $paymentObject->getRequest();
        $request->authentification(...$this->authentication->getAuthenticationArray());
        $request->customerAddress(...$this->customerData->getCustomerDataArray());
        $request->company(...$this->company->getCompanyDataArray());

        $home = new HomeParameterGroup();
        $home->street = 'Vangerowstr. 18';
        $home->city = 'Heidelberg';
        $home->country = 'DE';
        $home->zip = '69115';
        $executiveOne = [
            'OWNER',
            null,
            'Testkäufer',
            'Händler',
            '1988-12-12',
            'example@email.de',
            '062216471400',
            $home
        ];

        $executiveTwo = [
            'OWNER',
            null,
            null,
            null,
            '123',
            null,
            null,
            null
        ];

        $request->getCompany()->addExecutive(...$executiveOne);
        $request->getCompany()->addExecutive(...$executiveTwo);
        $paymentObject->dryRun = false;

        $this->paymentObject = $paymentObject;

        $this->mockCurlAdapter();
    }

    /**
     * Tear down function will remove all registered test doubles (i.e. Mocks)
     */
    // @codingStandardsIgnoreStart
    public function _after()
    {
        // @codingStandardsIgnoreEnd
        $this->paymentObject = null;
        test::clean();
    }

    /**
     * Prepare request company.
     * This test will convert the request object to post array format
     * @test
     */
    public function companyParametersShouldBeSetAsExpected()
    {
        $expectedRequestArray = [
            'COMPANY.COMPANYNAME' => 'heidelpay GmbH',
            'COMPANY.REGISTRATIONTYPE' => RegistrationType::REGISTERED,
            'COMPANY.COMMERCIALREGISTERNUMBER' => '123456789',
            'COMPANY.VATID' => '123456',
            'COMPANY.COMMERCIALSECTOR' => CommercialSector::AIR_TRANSPORT,
            'CRITERION.SDK_NAME' => 'Heidelpay\PhpPaymentApi',
            'CRITERION.SDK_VERSION' => 'v1.6.2',
            'FRONTEND.ENABLED' => 'TRUE',
            'FRONTEND.MODE' => 'WHITELABEL',
            'REQUEST.VERSION' => '1.0',
            'TRANSACTION.MODE' => 'CONNECTOR_TEST',
            'COMPANY.LOCATION.STREET' => 'street',
            'COMPANY.LOCATION.POBOX' => 'poBox',
            'COMPANY.LOCATION.ZIP' => 'zip',
            'COMPANY.LOCATION.CITY' => 'city',
            'COMPANY.LOCATION.COUNTRY' => 'country',
            'COMPANY.EXECUTIVE.1.FUNCTION' => 'OWNER',
            'COMPANY.EXECUTIVE.1.BIRTHDATE' => '1988-12-12',
            'COMPANY.EXECUTIVE.1.GIVEN' => 'Testkäufer',
            'COMPANY.EXECUTIVE.1.FAMILY' => 'Händler',
            'COMPANY.EXECUTIVE.1.EMAIL' => 'example@email.de',
            'COMPANY.EXECUTIVE.1.PHONE' => '062216471400',
            'COMPANY.EXECUTIVE.1.HOME.STREET' => 'Vangerowstr. 18',
            'COMPANY.EXECUTIVE.1.HOME.ZIP' => '69115',
            'COMPANY.EXECUTIVE.1.HOME.CITY' => 'Heidelberg',
            'COMPANY.EXECUTIVE.1.HOME.COUNTRY' => 'DE',
            'COMPANY.EXECUTIVE.2.FUNCTION' => 'OWNER',
            'COMPANY.EXECUTIVE.2.BIRTHDATE' => '123',
            'ADDRESS.CITY' => 'Heidelberg',
            'ADDRESS.COUNTRY' => 'DE',
            'ADDRESS.STATE' => 'DE-BW',
            'ADDRESS.STREET' => 'Vangerowstr. 18',
            'ADDRESS.ZIP' => '69115',
            'CONTACT.EMAIL' => 'development@heidelpay.com',
            'IDENTIFICATION.SHOPPERID' => '1234',
            'NAME.GIVEN' => 'Heidel',
            'NAME.FAMILY' => 'Berger-Payment',
            'SECURITY.SENDER' => '31HA07BC8142C5A171745D00AD63D182',
            'TRANSACTION.CHANNEL' => '31HA07BC8142C5A171744F3D6D155865',
            'USER.LOGIN' => '31ha07bc8142c5a171744e5aef11ffd3',
            'USER.PWD' => '93167DE7',
        ];

        $request = $this->paymentObject->getRequest();

        $this->assertThat($expectedRequestArray, $this->arraysMatchExactly($request->toArray()));
    }

    /**
     * @test
     */
    public function requestPostArrayShouldBeMappedAsExpected()
    {
        $postResponse = [
            'COMPANY_COMMERCIALREGISTERNUMBER' => '123456789',
            'COMPANY_COMMERCIALSECTOR' => 'AIR_TRANSPORT',
            'COMPANY_COMPANYNAME' => 'heidelpay GmbH',
            'COMPANY_EXECUTIVE_1_BIRTHDATE' => '1988-12-12',
            'COMPANY_EXECUTIVE_1_EMAIL' => 'example@email.de',
            'COMPANY_EXECUTIVE_1_FAMILY' => 'Händler',
            'COMPANY_EXECUTIVE_1_FUNCTION' => 'OWNER',
            'COMPANY_EXECUTIVE_1_GIVEN' => 'Testkäufer',
            'COMPANY_EXECUTIVE_1_HOME_CITY' => 'Heidelberg',
            'COMPANY_EXECUTIVE_1_HOME_COUNTRY' => 'DE',
            'COMPANY_EXECUTIVE_1_HOME_STREET' => 'Vangerowstr. 18',
            'COMPANY_EXECUTIVE_1_HOME_ZIP' => '69115',
            'COMPANY_EXECUTIVE_1_PHONE' => '062216471400',
            'COMPANY_EXECUTIVE_2_FUNCTION' => 'OWNER',
            'COMPANY_EXECUTIVE_2_BIRTHDATE' => '123',
            'COMPANY_LOCATION_POBOX' => 'poBox',
            'COMPANY_LOCATION_CITY' => 'city',
            'COMPANY_LOCATION_COUNTRY' => 'country',
            'COMPANY_LOCATION_STREET' => 'street',
            'COMPANY_LOCATION_ZIP' => 'zip',
            'COMPANY_REGISTRATIONTYPE' => 'REGISTERED',
            'COMPANY_VATID' => '123456',
        ];

        $this->assertEquals(
            $this->paymentObject->getRequest()->getCompany(),
            Request::fromPost($postResponse)->getCompany()
        );

    }

    /**
     * @test
     */
    public function fromJsonShouldBeMapped()
    {
        $request = new Request();
        $executiveOne = [
            'OWNER',
            null,
            'Testkäufer',
            'Händler',
            '1988-12-12',
            'example@email.de',
            '062216471400',
            null
        ];

        $company = new Company();
        $company->getCompanyDataArray();
        $request->company(...$company->getCompanyDataArray());
        $request->getCompany()->addExecutive(...$executiveOne);

        $mappedRequest = Request::fromJson($request->toJson());
        $this->assertEquals($request, $mappedRequest);
    }
}
