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
use Heidelpay\Tests\PhpPaymentApi\Helper\BasePaymentMethodTest;

class InvoiceB2BSecuredPaymentMethodTest extends BasePaymentMethodTest
{
    /**
     * Set up function will create a payment method object for each test case
     *
     * @throws \Exception
     */
    // @codingStandardsIgnoreStart
    public function _before()
    {
        // @codingStandardsIgnoreEnd
        //$this->authentication->setTransactionChannel('31HA07BC8142C5A171744F3D6D155865');

        $paymentObject = new InvoiceB2CSecuredPaymentMethod();
        //$paymentObject->getRequest()->getTransaction()->setChannel('123456789');
        //$paymentObject->getRequest()->authentification(...$this->authentication->getAuthenticationArray());
        //$paymentObject->getRequest()->customerAddress(...$this->customerData->getCustomerDataArray());

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
     * Prepare request test company
     *
     * @desc This test will convert the request object to post array format
     * @group integrationTest
     * @test
     */
    public function companyParametersShouldBeSetAsExpected()
    {
        $request = $this->paymentObject->getRequest();
        $location = new LocationParameterGroup();
        $location->street = 'street';
        $location->pobox = 'poBox';
        $location->zip = 'zip';
        $location->city = 'city';
        $location->country = 'country';

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

        $request->companyExecutive(...$executiveOne);
        $request->companyExecutive(...$executiveTwo);
        $request->company(
            'heidelpay GmbH',
            'poBox',
            'street',
            'zip',
            'city',
            'country',
            CommercialSector::AIR_TRANSPORT,
            RegistrationType::REGISTERED,
            '123456789',
            '123456'
        );

        $referenceVars = array(
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
        );

        $this->assertEquals($referenceVars, $this->paymentObject->getRequest()->toArray(), 'test');
    }
}
