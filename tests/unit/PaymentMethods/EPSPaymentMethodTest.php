<?php

namespace Heidelpay\Tests\PhpPaymentApi\unit\PaymentMethods;

use AspectMock\Test as test;
use Exception;
use Heidelpay\PhpPaymentApi\ParameterGroups\RedirectParameterGroup;
use Heidelpay\PhpPaymentApi\PaymentMethods\EPSPaymentMethod;
use Heidelpay\PhpPaymentApi\Response;
use Heidelpay\Tests\PhpPaymentApi\Helper\BasePaymentMethodTest;
use PHPUnit\Framework\ExpectationFailedException;

class EPSPaymentMethodTest extends BasePaymentMethodTest
{
    /**
     * Set up function will create a payment method object for each test case
     *
     * @throws Exception
     */
    // @codingStandardsIgnoreStart
    public function _before()
    {
        // @codingStandardsIgnoreEnd
        $this->authentication->setTransactionChannel('31HA07BC8142C5A171744F3D6D155865');

        $paymentObject = new EPSPaymentMethod();

        $request = $paymentObject->getRequest();
        $request->authentification(...$this->authentication->getAuthenticationArray());
        $request->customerAddress(...$this->customerData->getCustomerDataArray());

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
     * @test
     *
     * @throws \PHPUnit\Framework\Exception
     * @throws ExpectationFailedException
     */
    public function requestPostArrayParamsShouldBeMappedAsExpected()
    {
        $url          = 'https://this-is-a-test-redirect-url-for-eps-testing.de';
        $firstParam   = 'This is the content of the first parameter';
        $secondParam  = 'This is the content of the second parameter';
        $postResponse = [
            'PROCESSING_REDIRECT_PARAMETER_FIRST' => $firstParam,
            'PROCESSING_REDIRECT_PARAMETER_SECOND' => $secondParam,
            'PROCESSING_REDIRECT_URL' => $url
        ];

        /** @var RedirectParameterGroup $redirect */
        $redirect = (new Response($postResponse))->getProcessing()->getRedirect();

        $this->assertEquals($url, $redirect->getUrl());
        $this->assertEquals(['first' => $firstParam, 'second' => $secondParam], $redirect->getParameter());
    }
}
