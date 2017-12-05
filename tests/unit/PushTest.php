<?php

namespace Heidelpay\Tests\PhpPaymentApi\Unit;

use Heidelpay\PhpPaymentApi\Exceptions\XmlResponseParserException;
use Heidelpay\PhpPaymentApi\Push;
use Heidelpay\PhpPaymentApi\PushMapping\Account;
use Heidelpay\PhpPaymentApi\PushMapping\Connector;
use Heidelpay\PhpPaymentApi\PushMapping\Payment;
use Heidelpay\PhpPaymentApi\PushMapping\Processing;
use Heidelpay\PhpPaymentApi\Response;
use Codeception\TestCase\Test;
use SimpleXMLElement;

/**
 * Push Test
 *
 * Unit Test for the XML Push Response Mapping
 *
 * @license Use of this software requires acceptance of the License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author Stephano Vogel
 *
 * @package heidelpay
 * @subpackage php-api
 * @category php-api
 */
class PushTest extends Test
{
    /**
     * @var Response|null
     */
    protected $response;

    /**
     * Example Credit Card Registration Response
     *
     * @var string
     */
    protected $xmlCcRegResponse;

    /**
     * Example Credit Card Debit (pending) Response (with 3DSecure)
     *
     * @var string
     */
    protected $xmlCcDbPendingResponse;

    /**
     * Example Direct Debit Response
     *
     * @var string
     */
    protected $xmlDdDbResponse;

    /**
     * Example Invoice Receipt Response
     *
     * @var string
     */
    protected $xmlIvRcResponse;

    /**
     * Example Prepayment Pre-Authorisation Response
     *
     * @var string
     */
    protected $xmlPpPaResponse;

    /**
     * Example invalid response that covers some null values
     *
     * @var string
     */
    protected $xmlInvalidResponse;

    /**
     * Sets up the fixture, for example, open a network connection.
     */
    // @codingStandardsIgnoreStart
    public function _before()
    {
        // @codingStandardsIgnoreEnd
        $this->setSampleCcRgResponse();
        $this->setSampleCcDbPendingResponse();
        $this->setSampleDdDbResponse();
        $this->setSampleIvRcResponse();
        $this->setSamplePpPaResponse();
        $this->setSampleInvalidResponse();
    }

    /**
     * @test
     */
    public function isMappedResponseClass()
    {
        $push = new Push($this->xmlCcRegResponse);
        $response = $push->getResponse();

        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * Tests if an exception will be thrown when no raw response is set.
     *
     * @test
     */
    public function throwXmlResponseParserException()
    {
        // set the raw xml response to null for having no valid string to parse.
        $this->xmlCcRegResponse = null;

        $this->expectException(XmlResponseParserException::class);
        $push = new Push($this->xmlCcRegResponse);
        $push->getResponse();
    }

    /**
     * This test validates all response values to be correct after the mapping process
     * for a credit card registration response.
     *
     * @test
     */
    public function hasValidMappedCcRgProperties()
    {
        $push = new Push();
        $push->setRawResponse($this->xmlCcRegResponse);
        $response = $push->getResponse();

        if (!($response instanceof Response)) {
            throw new \RuntimeException('Response is not set!');
        }

        $this->assertEquals('Heidel', $response->getName()->getGiven());
        $this->assertEquals('Berger-Payment', $response->getName()->getFamily());
        $this->assertEquals('MR', $response->getName()->getSalutation());

        $this->assertEquals('Vangerowstr. 18', $response->getAddress()->getStreet());
        $this->assertEquals('69115', $response->getAddress()->getZip());
        $this->assertEquals('Heidelberg', $response->getAddress()->getCity());
        $this->assertEquals('DE', $response->getAddress()->getCountry());
        $this->assertEquals('DE-BW', $response->getAddress()->getState());

        $this->assertEquals('development@heidelpay.de', $response->getContact()->getEmail());

        $this->assertEquals('2843294932', $response->getIdentification()->getTransactionId());
        $this->assertEquals('31HA07BC8108A9126F199F2784552637', $response->getIdentification()->getUniqueId());
        $this->assertEquals('3379.5447.1520', $response->getIdentification()->getShortId());
        $this->assertEquals('12344', $response->getIdentification()->getShopperId());

        $this->assertEquals('000.100.112', $response->getProcessing()->getReturnCode());
        $this->assertEquals(
            'Request successfully processed in \'Merchant in Connector Test Mode\'',
            $response->getProcessing()->getReturn()
        );
        $this->assertEquals('90', $response->getProcessing()->getStatusCode());
        $this->assertEquals('ACK', $response->getProcessing()->getResult());

        $this->assertEquals('23.12', $response->getPresentation()->getAmount());
        $this->assertNotEquals('12.34', $response->getPresentation()->getAmount());
        $this->assertEquals('EUR', $response->getPresentation()->getCurrency());

        $this->assertEquals('CC.RG', $response->getPayment()->getCode());

        $this->assertEquals('000.100.112', $response->getError()['code']);
        $this->assertEquals(
            'Request successfully processed in \'Merchant in Connector Test Mode\'',
            $response->getError()['message']
        );

        $this->assertTrue($response->isSuccess(), 'Response Status is not success.');
        $this->assertFalse($response->isError(), 'Response Status is error.');
        $this->assertFalse($response->isPending(), 'Response Status is pending.');

        $this->assertEquals(
            '209022666cd4706e5f451067592b6be1aff4a913d5bb7f8249f7418ee25c91b3'
            . '18ebac66f41a6692539c8923adfdad6aae26138b1b3a7e37a197ab952be57876',
            $response->getCriterion()->getSecretHash()
        );
    }

    /**
     * This test validates all response values to be correct after the mapping process
     * for a pending credit card debit response with 3d secure.
     *
     * @test
     */
    public function hasValidMappedCcDbPendingProperties()
    {
        $push = new Push($this->xmlCcDbPendingResponse);
        $response = $push->getResponse();

        if (!($response instanceof Response)) {
            throw new \RuntimeException('Response is not set');
        }
        $this->assertEquals('Heidel', $response->getName()->getGiven());
        $this->assertEquals('Berger-Payment', $response->getName()->getFamily());

        $this->assertEquals('Vangerowstr. 18', $response->getAddress()->getStreet());
        $this->assertEquals('69115', $response->getAddress()->getZip());
        $this->assertEquals('Heidelberg', $response->getAddress()->getCity());
        $this->assertEquals('DE', $response->getAddress()->getCountry());

        $this->assertEquals('development@heidelpay.de', $response->getContact()->getEmail());

        $this->assertEquals('Heidel Berger-Payment', $response->getAccount()->getHolder());
        $this->assertEquals('471110******0000', $response->getAccount()->getNumber());
        $this->assertEquals('VISA', $response->getAccount()->getBrand());
        $this->assertEquals('02', $response->getAccount()->getExpiryMonth());
        $this->assertEquals('2019', $response->getAccount()->getExpiryYear());

        $this->assertEquals('145000035', $response->getIdentification()->getTransactionId());
        $this->assertEquals('31HA07BC81756B7E77990451F3B9C082', $response->getIdentification()->getUniqueId());
        $this->assertEquals('3552.1735.8428', $response->getIdentification()->getShortId());
        $this->assertEquals('140', $response->getIdentification()->getShopperId());

        $this->assertEquals('000.200.000', $response->getProcessing()->getReturnCode());
        $this->assertEquals('Transaction pending', $response->getProcessing()->getReturn());
        $this->assertEquals('80', $response->getProcessing()->getStatusCode());
        $this->assertEquals('ACK', $response->getProcessing()->getResult());

        $this->assertEquals('150.37', $response->getPresentation()->getAmount());
        $this->assertNotEquals('15.37', $response->getPresentation()->getAmount());
        $this->assertEquals('EUR', $response->getPresentation()->getCurrency());

        $this->assertEquals('CC.DB', $response->getPayment()->getCode());

        $this->assertEquals('000.200.000', $response->getError()['code']);
        $this->assertEquals('Transaction pending', $response->getError()['message']);

        $this->assertTrue($response->isPending(), 'Response Status is not pending.');
        $this->assertTrue($response->isSuccess(), 'Response Status is not success.');
        $this->assertFalse($response->isError(), 'Response Status is error.');

        $this->assertEquals(
            'efe14520c747b753fb91c613c421f8b5ca0c51c4df0b35f3ca6d3204039bc283',
            $response->getCriterion()->getSecretHash()
        );
    }

    /**
     * @test
     */
    public function hasValidMappedDdDbProperties()
    {
        $push = new Push($this->xmlDdDbResponse);
        $response = $push->getResponse();

        if (!($response instanceof Response)) {
            throw new \RuntimeException('Response is not set!');
        }

        $this->assertEquals('Heidel', $response->getName()->getGiven());
        $this->assertEquals('Berger-Payment', $response->getName()->getFamily());
        $this->assertEquals('MR', $response->getName()->getSalutation());

        $this->assertEquals('Vangerowstr. 18', $response->getAddress()->getStreet());
        $this->assertEquals('69115', $response->getAddress()->getZip());
        $this->assertEquals('Heidelberg', $response->getAddress()->getCity());
        $this->assertEquals('DE', $response->getAddress()->getCountry());

        $this->assertEquals('development@heidelpay.de', $response->getContact()->getEmail());

        $this->assertEquals('Heidel Berger-Payment', $response->getAccount()->getHolder());
        $this->assertEquals('DE89370400440532013000', $response->getAccount()->getIban());
        $this->assertEquals('COBADEFFXXX', $response->getAccount()->getBic());
        $this->assertEquals('DE', $response->getAccount()->getCountry());
        $this->assertEquals('3549.0213.2358', $response->getAccount()->getIdentification());
        $this->assertEquals('COMMERZBANK KÖLN', $response->getAccount()->getBankName());
        /** @noinspection PhpDeprecationInspection */
        $this->assertEquals('37040044', $response->getAccount()->getBank());
        $this->assertEquals('532013000', $response->getAccount()->getNumber());

        $this->assertEquals('283', $response->getIdentification()->getTransactionId());
        $this->assertEquals('31HA07BC81388C2CADC0495091DBE5B4', $response->getIdentification()->getUniqueId());
        $this->assertEquals('DE87ZZZ00000019937', $response->getIdentification()->getCreditorId());
        $this->assertEquals('3549.0213.2358', $response->getIdentification()->getShortId());
        $this->assertEquals('1', $response->getIdentification()->getShopperId());

        $this->assertEquals('000.100.112', $response->getProcessing()->getReturnCode());
        $this->assertEquals(
            'Request successfully processed in \'Merchant in Connector Test Mode\'',
            $response->getProcessing()->getReturn()
        );
        $this->assertEquals('90', $response->getProcessing()->getStatusCode());
        $this->assertEquals('ACK', $response->getProcessing()->getResult());

        $this->assertEquals('51.00', $response->getPresentation()->getAmount());
        $this->assertNotEquals('510.00', $response->getPresentation()->getAmount());
        $this->assertEquals('EUR', $response->getPresentation()->getCurrency());

        $this->assertEquals('DD.DB', $response->getPayment()->getCode());
    }

    /**
     * This test validates all response values to be correct after the mapping process
     * for a invoice receipt response.
     *
     * @test
     */
    public function hasValidMappedIvRcProperties()
    {
        $push = new Push($this->xmlIvRcResponse);
        $response = $push->getResponse();

        if (!($response instanceof Response)) {
            throw new \RuntimeException('Response is not set!');
        }

        $this->assertEquals('Heidel', $response->getName()->getGiven());
        $this->assertEquals('Berger-Payment', $response->getName()->getFamily());
        $this->assertEquals('MR', $response->getName()->getSalutation());

        $this->assertEquals('Vangerowstr. 18', $response->getAddress()->getStreet());
        $this->assertEquals('69115', $response->getAddress()->getZip());
        $this->assertEquals('Heidelberg', $response->getAddress()->getCity());
        $this->assertEquals('DE', $response->getAddress()->getCountry());

        $this->assertEquals('development@heidelpay.de', $response->getContact()->getEmail());

        $this->assertEquals('308', $response->getIdentification()->getTransactionId());
        $this->assertEquals('31HA07BC816DC116ADA43CE7704010DE', $response->getIdentification()->getUniqueId());
        $this->assertEquals('31HA07BC810345131D99026AA71DFDF4', $response->getIdentification()->getReferenceId());
        $this->assertEquals('DE87ZZZ00000019937', $response->getIdentification()->getCreditorId());
        $this->assertEquals('3559.2749.2534', $response->getIdentification()->getShortId());
        $this->assertEquals('1', $response->getIdentification()->getShopperId());

        $this->assertEquals('000.100.112', $response->getProcessing()->getReturnCode());
        $this->assertEquals(
            'Request successfully processed in \'Merchant in Connector Test Mode\'',
            $response->getProcessing()->getReturn()
        );
        $this->assertEquals('90', $response->getProcessing()->getStatusCode());
        $this->assertEquals('ACK', $response->getProcessing()->getResult());

        $this->assertEquals('56.99', $response->getPresentation()->getAmount());
        $this->assertNotEquals('57.00', $response->getPresentation()->getAmount());
        $this->assertEquals('EUR', $response->getPresentation()->getCurrency());

        $this->assertEquals('IV.RC', $response->getPayment()->getCode());

        $this->assertEquals('000.100.112', $response->getError()['code']);
        $this->assertEquals(
            'Request successfully processed in \'Merchant in Connector Test Mode\'',
            $response->getError()['message']
        );

        $this->assertTrue($response->isSuccess(), 'Response Status is not success.');
        $this->assertFalse($response->isError(), 'Response Status is not error.');
        $this->assertFalse($response->isPending(), 'Response Status is not pending.');

        $this->assertEquals(
            '40a18e0318822bf21f273e5f581834aaa6f169656dfaa6e826e767f4eddc6fe8'
            . '4e6c1e0974392997a2c6e68a921162f22a431053ff8fb0d37f1dc0cc8bc46c25',
            $response->getCriterion()->getSecretHash()
        );
    }

    /**
     * This test validates all response values to be correct after the mapping process
     * for a prepayment pre-authorisation response.
     *
     * @test
     */
    public function hasValidMappedPpPaProperties()
    {
        $push = new Push($this->xmlPpPaResponse);
        $response = $push->getResponse();

        if (!($response instanceof Response)) {
            throw new \RuntimeException('Response is not set!');
        }

        $this->assertEquals('Heidel', $response->getName()->getGiven());
        $this->assertEquals('Berger-Payment', $response->getName()->getFamily());

        $this->assertEquals('Vangerowstr. 18', $response->getAddress()->getStreet());
        $this->assertEquals('69115', $response->getAddress()->getZip());
        $this->assertEquals('Heidelberg', $response->getAddress()->getCity());
        $this->assertEquals('DE', $response->getAddress()->getCountry());

        $this->assertEquals('DE', $response->getConnector()->getAccountCountry());
        $this->assertEquals('COBADEFFXXX', $response->getConnector()->getAccountBic());
        $this->assertEquals('Heidelberger Payment GmbH', $response->getConnector()->getAccountHolder());
        $this->assertEquals('DE89370400440532013000', $response->getConnector()->getAccountIBan());
        /** @noinspection PhpDeprecationInspection */
        $this->assertEquals('37040044', $response->getConnector()->getAccountBank());
        /** @noinspection PhpDeprecationInspection */
        $this->assertEquals('5320130', $response->getConnector()->getAccountNumber());

        $this->assertEquals('development@heidelpay.de', $response->getContact()->getEmail());

        $this->assertEquals('303', $response->getIdentification()->getTransactionId());
        $this->assertEquals('31HA07BC81561F9B12B3008CBC742D4E', $response->getIdentification()->getUniqueId());
        $this->assertEquals('3551.5515.6106', $response->getIdentification()->getShortId());
        $this->assertEquals('1', $response->getIdentification()->getShopperId());
        $this->assertEquals('13213213344324324', $response->getIdentification()->getCreditorId());

        $this->assertEquals('000.100.112', $response->getProcessing()->getReturnCode());
        $this->assertEquals(
            'Request successfully processed in \'Merchant in Connector Test Mode\'',
            $response->getProcessing()->getReturn()
        );
        $this->assertEquals('90', $response->getProcessing()->getStatusCode());
        $this->assertEquals('ACK', $response->getProcessing()->getResult());

        $this->assertEquals('107.00', $response->getPresentation()->getAmount());
        $this->assertNotEquals('106.99', $response->getPresentation()->getAmount());
        $this->assertEquals('EUR', $response->getPresentation()->getCurrency());

        $this->assertEquals('PP.PA', $response->getPayment()->getCode());

        $this->assertEquals('000.100.112', $response->getError()['code']);
        $this->assertEquals(
            'Request successfully processed in \'Merchant in Connector Test Mode\'',
            $response->getError()['message']
        );

        $this->assertTrue($response->isSuccess(), 'Response Status is not success.');
        $this->assertFalse($response->isError(), 'Response Status is not error.');
        $this->assertFalse($response->isPending(), 'Response Status is not pending.');

        $this->assertEquals(
            '305e17d3b341233bb08fa37c2761134baffcc30c1bba205655c64fcc196fccb6'
            . '59a9f8aff5e237c60ffcae600e1f11e7342f60dbdd43b1cdc1a17a323c3f753d',
            $response->getCriterion()->getSecretHash()
        );
    }

    /**
     * @test
     */
    public function hasExceptedValuesOnIncompleteResponse()
    {
        $push = new Push($this->xmlInvalidResponse);
        $response = $push->getResponse();

        if (!($response instanceof Response)) {
            throw new \RuntimeException('Response is not set!');
        }

        $this->assertEquals(null, $response->getPayment()->getCode());
        $this->assertEquals(null, $response->getProcessing()->getReturnCode());
        $this->assertEquals(null, $response->getProcessing()->getStatusCode());
        $this->assertEquals(null, $response->getProcessing()->code);
        $this->assertEquals(null, $response->getTransaction()->getChannel());
        $this->assertEquals('CONNECTOR_TEST', $response->getTransaction()->getMode());
    }

    /**
     * Verify abstract implementations of getXmlObjectField.
     *
     * @test
     */
    public function abstractGetXmlObjectFieldGetterImplementationShouldReturnNull()
    {
        $xmlElement = new SimpleXMLElement($this->setSampleDataForAbstractGetterTest());

        // does not implement the tested method
        $address = new Payment();
        $this->assertEquals(null, $address->getXmlObjectField($xmlElement, 'Holder'));

        // implements the tested method
        $connector = new Connector();
        $this->assertEquals('Heidelberger Payment GmbH', $connector->getXmlObjectField($xmlElement, 'Holder'));
    }

    /**
     * Verify abstract implementations of getXmlObjectFieldAttribute.
     *
     * @test
     */
    public function abstractGetXmlObjectFieldAttributeGetterImplementationShouldReturnNull()
    {
        $xmlElement = new SimpleXMLElement($this->setSampleDataForAbstractGetterTest());

        // does not implement the tested method
        $address = new Payment();
        $this->assertEquals(null, $address->getXmlObjectFieldAttribute($xmlElement, 'Status:code'));

        // implements the tested method
        $connector = new Processing();
        $this->assertEquals(90, $connector->getXmlObjectFieldAttribute($xmlElement, 'Status:code'));
    }

    /**
     * Verify abstract implementations of getXmlObjectFieldAttribute.
     *
     * @test
     */
    public function abstractGetXmlObjectPropertyGetterImplementationShouldReturnNull()
    {
        $xmlElement = new SimpleXMLElement($this->setSampleDataForAbstractGetterTest());

        // does not implement the tested method
        $connector = new Account();
        $this->assertEquals(null, $connector->getXmlObjectProperty($xmlElement, 'code'));

        // implements the tested method
        $address = new Payment();
        $this->assertEquals('CC.RG', $address->getXmlObjectProperty($xmlElement, 'code'));
    }

    /**
     * XML data for abstractGetXmlObjectFieldGetterImplementationShouldReturnNull,
     *
     * @return string
     */
    private function setSampleDataForAbstractGetterTest()
    {
        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8" ?>
<Response version="1.0">
    <Transaction mode="CONNECTOR_TEST" response="SYNC" channel="31HA07BC8142C5A171744F3D6D155865" source="HIP">
        <Processing>
            <Status code="90" /> <!-- XmlObjectFieldAttribute -->
        </Processing>
        <Payment code="CC.RG"> <!-- XmlObjectProperty -->
            <Status code="90" /> <!-- XmlObjectFieldAttribute -->
            <Holder>Heidelberger Payment GmbH</Holder> <!-- XmlObjectField -->
        </Payment>
        <Connector>
            <Account code="CC.RG"> <!-- XmlObjectProperty -->
                <Holder>Heidelberger Payment GmbH</Holder> <!-- XmlObjectField -->
            </Account>
        </Connector>
    </Transaction>
</Response>
XML;

        return $xml;
    }

    private function setSampleCcRgResponse()
    {
        $this->xmlCcRegResponse = <<<XML
<?xml version="1.0" encoding="UTF-8" ?>
<Response version="1.0">
    <Transaction mode="CONNECTOR_TEST" response="SYNC" channel="31HA07BC8142C5A171744F3D6D155865" source="HIP">
        <Identification>
            <TransactionID>2843294932</TransactionID>
            <UniqueID>31HA07BC8108A9126F199F2784552637</UniqueID>
            <ShortID>3379.5447.1520</ShortID>
            <ShopperID>12344</ShopperID>
            <Source>HPC</Source>
        </Identification>
        <Processing code="CC.RG.90.00">
            <Timestamp>2016-09-16 12:14:31</Timestamp>
            <Result>ACK</Result>
            <Status code="90">NEW</Status>
            <Reason code="00">SUCCESSFULL</Reason>
            <Return code="000.100.112">Request successfully processed in 'Merchant in Connector Test Mode'</Return>
        </Processing>
        <Payment code="CC.RG">
            <Presentation>
                <Amount>23.12</Amount>
                <Currency>EUR</Currency>
            </Presentation>
        </Payment>
        <Customer>
            <Name>
                <Given>Heidel</Given>
                <Family>Berger-Payment</Family>
                <Salutation>MR</Salutation>
            </Name>
            <Address>
                <Street>Vangerowstr. 18</Street>
                <Zip>69115</Zip>
                <City>Heidelberg</City>
                <Country>DE</Country>
                <State>DE-BW</State>
            </Address>
            <Contact>
                <Email>development@heidelpay.de</Email>
            </Contact>
        </Customer>
        <Frontend>
            <ResponseUrl>http://dev.heidelpay.de/response.php</ResponseUrl>
        </Frontend>
        <Analysis>
            <Criterion name="PUSH_URL">http://dev.heidelpay.de/push.php</Criterion>
            <Criterion name="SDK_NAME">Heidelpay\PhpPaymentApi</Criterion>
            <Criterion name="SECRET">209022666cd4706e5f451067592b6be1aff4a913d5bb7f8249f7418ee25c91b318ebac66f41a6692539c8923adfdad6aae26138b1b3a7e37a197ab952be57876</Criterion>
            <Criterion name="GUEST">false</Criterion>
            <Criterion name="SDK_VERSION">17.4.13</Criterion>
        </Analysis>
        <RequestTimestamp>2016-09-16 12:14:31</RequestTimestamp>
    </Transaction>
</Response>
XML;
    }

    private function setSampleCcDbPendingResponse()
    {
        $this->xmlCcDbPendingResponse = <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Response version="1.0">
	<Transaction mode="CONNECTOR_TEST" response="ASYNC" channel="31HA07BC8142C5A171749A60D979B6E4" source="WPF">
		<Identification>
			<TransactionID>145000035</TransactionID>
			<UniqueID>31HA07BC81756B7E77990451F3B9C082</UniqueID>
			<ShortID>3552.1735.8428</ShortID>
			<ShopperID>140</ShopperID>
			<Source>HPC</Source>
		</Identification>
		<Processing code="CC.DB.80.00">
			<Timestamp>2017-04-04 07:29:18</Timestamp>
			<Result>ACK</Result>
			<Status code="80">WAITING</Status>
			<Reason code="00">Transaction Pending</Reason>
			<Return code="000.200.000">Transaction pending</Return>
		</Processing>
		<Payment code="CC.DB">
			<Presentation>
				<Amount>150.37</Amount>
				<Currency>EUR</Currency>
			</Presentation>
		</Payment>
		<Account>
			<Number>471110******0000</Number>
			<Holder>Heidel Berger-Payment</Holder>
			<Expiry month="02" year="2019"/>
			<Month>02</Month>
			<Year>2019</Year>
			<Brand>VISA</Brand>
		</Account>
		<Customer>
			<Name>
				<Given>Heidel</Given>
                <Family>Berger-Payment</Family>
			</Name>
			<Address>
				<Street>Vangerowstr. 18</Street>
                <Zip>69115</Zip>
                <City>Heidelberg</City>
                <Country>DE</Country>
			</Address>
			<Contact>
				<Email>development@heidelpay.de</Email>
			</Contact>
		</Customer>
		<Frontend>
			<ResponseUrl>http://dev.heidelpay.de/response.php</ResponseUrl>
		</Frontend>
		<Analysis>
			<Criterion name="SECRET">efe14520c747b753fb91c613c421f8b5ca0c51c4df0b35f3ca6d3204039bc283</Criterion>
			<Criterion name="PUSH_URL">http://dev.heidelpay.de/push.php</Criterion>
			<Criterion name="GUEST">false</Criterion>
		</Analysis>
		<RequestTimestamp>2017-04-04 07:29:18</RequestTimestamp>
	</Transaction>
</Response>
XML;
    }

    private function setSampleDdDbResponse()
    {
        $this->xmlDdDbResponse = <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Response version="1.0">
    <Transaction mode="CONNECTOR_TEST" response="SYNC" channel="31HA07BC8142C5A171749A60D979B6E4" source="WPF">
        <Identification>
            <TransactionID>283</TransactionID>
            <UniqueID>31HA07BC81388C2CADC0495091DBE5B4</UniqueID>
            <ShortID>3549.0213.2358</ShortID>
            <ShopperID>1</ShopperID>
            <Source>HPC</Source>
            <CreditorID>DE87ZZZ00000019937</CreditorID>
        </Identification>
        <Processing code="DD.DB.90.00">
            <Timestamp>2017-03-31 15:55:32</Timestamp>
            <Result>ACK</Result>
            <Status code="90">NEW</Status>
            <Reason code="00">SUCCESSFULL</Reason>
            <Return code="000.100.112">Request successfully processed in 'Merchant in Connector Test Mode'</Return>
        </Processing>
        <Payment code="DD.DB">
            <Presentation>
                <Amount>51.00</Amount>
                <Currency>EUR</Currency>
            </Presentation>
        </Payment>
        <Account>
            <Number>532013000</Number>
            <Holder>Heidel Berger-Payment</Holder>
            <Bank>37040044</Bank>
            <Country>DE</Country>
            <Expiry/>
            <Bic>COBADEFFXXX</Bic>
            <Iban>DE89370400440532013000</Iban>
            <BankName>COMMERZBANK KÖLN</BankName>
            <Identification>3549.0213.2358</Identification>
        </Account>
        <Customer>
            <Name>
                <Salutation>MR</Salutation>
                <Given>Heidel</Given>
                <Family>Berger-Payment</Family>
            </Name>
            <Address>
                <Street>Vangerowstr. 18</Street>
                <Zip>69115</Zip>
                <City>Heidelberg</City>
                <Country>DE</Country>
            </Address>
            <Contact>
                <Email>development@heidelpay.de</Email>
            </Contact>
        </Customer>
        <Analysis>
            <Criterion name="PAYMENT_METHOD">DirectDebitPaymentMethod</Criterion>
            <Criterion name="SDK_NAME">Heidelpay\PhpPaymentApi</Criterion>
            <Criterion name="SHOPMODULE.VERSION">Heidelpay Gateway 17.3.31</Criterion>
            <Criterion name="SECRET">a5a39cd99f11c247dbfaa2b9077000b7ee298947c4c90dcbe1043d46ffac8dbf72c9597ed71ab4c3d1ccebe85ba45c615ff1878e01e90b58e55da5e971157dff</Criterion>
            <Criterion name="SDK_VERSION">17.3.2</Criterion>
            <Criterion name="PUSH_URL">http://dev.heidelpay.de/push.php</Criterion>
            <Criterion name="GUEST">false</Criterion>
        </Analysis>
        <RequestTimestamp>2017-03-31 15:55:32</RequestTimestamp>
    </Transaction>
</Response>
XML;
    }

    private function setSampleIvRcResponse()
    {
        $this->xmlIvRcResponse = <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Response version="1.0">
    <Transaction mode="CONNECTOR_TEST" response="SYNC" channel="31HA07BC8142C5A171749A60D979B6E4" source="HIP">
        <Identification>
            <TransactionID>308</TransactionID>
            <UniqueID>31HA07BC816DC116ADA43CE7704010DE</UniqueID>
            <ShortID>3559.2749.2534</ShortID>
            <ShopperID>1</ShopperID>
            <Source>HPC</Source>
            <ReferenceID>31HA07BC810345131D99026AA71DFDF4</ReferenceID>
            <CreditorID>DE87ZZZ00000019937</CreditorID>
        </Identification>
        <Processing code="IV.RC.90.00">
            <Timestamp>2017-04-12 12:44:52</Timestamp>
            <Result>ACK</Result>
            <Status code="90">NEW</Status>
            <Reason code="00">SUCCESSFULL</Reason>
            <Return code="000.100.112">Request successfully processed in 'Merchant in Connector Test Mode'</Return>
        </Processing>
        <Payment code="IV.RC">
            <Presentation>
                <Amount>56.99</Amount>
                <Currency>EUR</Currency>
            </Presentation>
        </Payment>
        <Customer>
            <Name>
                <Given>Heidel</Given>
                <Family>Berger-Payment</Family>
                <Salutation>MR</Salutation>
            </Name>
            <Address>
                <Street>Vangerowstr. 18</Street>
                <Zip>69115</Zip>
                <City>Heidelberg</City>
                <Country>DE</Country>
            </Address>
            <Contact>
                <Email>development@heidelpay.de</Email>
            </Contact>
        </Customer>
        <Frontend>
            <ResponseUrl>http://dev.heidelpay.de/response.php</ResponseUrl>
        </Frontend>
        <Analysis>
            <Criterion name="PUSH_URL">http://dev.heidelpay.de/push.php</Criterion>
            <Criterion name="SDK_NAME">Heidelpay\PhpPaymentApi</Criterion>
            <Criterion name="PAYMENT_METHOD">InvoicePaymentMethod</Criterion>
            <Criterion name="SECRET">40a18e0318822bf21f273e5f581834aaa6f169656dfaa6e826e767f4eddc6fe84e6c1e0974392997a2c6e68a921162f22a431053ff8fb0d37f1dc0cc8bc46c25</Criterion>
            <Criterion name="GUEST">false</Criterion>
            <Criterion name="SDK_VERSION">17.3.2</Criterion>
        </Analysis>
        <RequestTimestamp>2017-04-12 12:44:52</RequestTimestamp>
    </Transaction>
</Response>
XML;
    }

    private function setSamplePpPaResponse()
    {
        $this->xmlPpPaResponse = <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Response version="1.0">
    <Transaction mode="CONNECTOR_TEST" response="SYNC" channel="31HA07BC8142C5A171749A60D979B6E4" source="WPF">
        <Identification>
            <TransactionID>303</TransactionID>
            <UniqueID>31HA07BC81561F9B12B3008CBC742D4E</UniqueID>
            <ShortID>3551.5515.6106</ShortID>
            <ShopperID>1</ShopperID>
            <Source>HPC</Source>
            <CreditorID>13213213344324324</CreditorID>
        </Identification>
        <Processing code="PP.PA.90.00">
            <Timestamp>2017-04-03 14:12:36</Timestamp>
            <Result>ACK</Result>
            <Status code="90">NEW</Status>
            <Reason code="00">SUCCESSFULL</Reason>
            <Return code="000.100.112">Request successfully processed in 'Merchant in Connector Test Mode'</Return>
        </Processing>
        <Payment code="PP.PA">
            <Presentation>
                <Amount>107.00</Amount>
                <Currency>EUR</Currency>
            </Presentation>
        </Payment>
        <Connector>
            <Account>
                <Country>DE</Country>
                <Bic>COBADEFFXXX</Bic>
                <Bank>37040044</Bank>
                <Number>5320130</Number>
                <Holder>Heidelberger Payment GmbH</Holder>
                <Iban>DE89370400440532013000</Iban>
            </Account>
        </Connector>
        <Customer>
            <Name>
                <Given>Heidel</Given>
                <Family>Berger-Payment</Family>
            </Name>
            <Address>
                <Street>Vangerowstr. 18</Street>
                <Zip>69115</Zip>
                <City>Heidelberg</City>
                <Country>DE</Country>
            </Address>
            <Contact>
                <Email>development@heidelpay.de</Email>
            </Contact>
        </Customer>
        <Analysis>
            <Criterion name="PAYMENT_METHOD">PrepaymentPaymentMethod</Criterion>
            <Criterion name="SDK_NAME">Heidelpay\PhpPaymentApi</Criterion>
            <Criterion name="SHOPMODULE.VERSION">Heidelpay Gateway 17.3.31</Criterion>
            <Criterion name="SECRET">305e17d3b341233bb08fa37c2761134baffcc30c1bba205655c64fcc196fccb659a9f8aff5e237c60ffcae600e1f11e7342f60dbdd43b1cdc1a17a323c3f753d</Criterion>
            <Criterion name="SDK_VERSION">17.3.2</Criterion>
            <Criterion name="PUSH_URL">http://dev.heidelpay.de/push.php</Criterion>
            <Criterion name="GUEST">false</Criterion>
        </Analysis>
        <RequestTimestamp>2017-04-03 14:12:36</RequestTimestamp>
    </Transaction>
</Response>
XML;
    }

    private function setSampleInvalidResponse()
    {
        $this->xmlInvalidResponse = <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Response version="1.0">
    <Transaction response="SYNC" source="WPF">
        <Processing>
            <Timestamp>2017-04-03 14:12:36</Timestamp>
            <Result>ACK</Result>
            <Status>NEW</Status>
            <Reason>SUCCESSFULL</Reason>
            <Return>Request successfully processed in 'Merchant in Connector Test Mode'</Return>
        </Processing>
    </Transaction>
</Response>
XML;
    }
}
