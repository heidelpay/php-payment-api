<?php

namespace Heidelpay\Tests\PhpApi\Unit;

use Heidelpay\PhpApi\Exceptions\XmlResponseParserException;
use Heidelpay\PhpApi\Push;
use Heidelpay\PhpApi\Response;
use PHPUnit\Framework\TestCase;

/**
 * Push Test
 *
 * Unit Test for the XML Push Response Mapping
 *
 * @license Use of this software requires acceptance of the License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 * @link https://dev.heidelpay.de/php-api
 *
 * @author Stephano Vogel
 *
 * @package heidelpay
 * @subpackage php-api
 * @category php-api
 */
class PushTest extends TestCase
{
    /**
     * @var Response|null
     */
    protected $response;

    /**
     * @var string
     */
    protected $xmlCcRegResponse;

    /**
     * setUp sample response Object
     *
     * @inheritdoc
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
        $this->xmlCcRegResponse = <<<XML
<?xml version="1.0" encoding="UTF-8" ?>
<Response version="1.0">
    <Transaction mode="CONNECTOR_TEST" response="SYNC" channel="31HA07BC81895ACFE22C154CBC521922" source="HIP">
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
            <Criterion name="SDK_NAME">Heidelpay\PhpApi</Criterion>
            <Criterion name="SECRET">209022666cd4706e5f451067592b6be1aff4a913d5bb7f8249f7418ee25c91b318ebac66f41a6692539c8923adfdad6aae26138b1b3a7e37a197ab952be57876</Criterion>
            <Criterion name="GUEST">false</Criterion>
            <Criterion name="SDK_VERSION">17.4.13</Criterion>
        </Analysis>
        <RequestTimestamp>2016-09-16 12:14:31</RequestTimestamp>
    </Transaction>
</Response>
XML;
    }

    /**
     * @test
     */
    public function isMappedResponseClass()
    {
        $push = new Push($this->xmlCcRegResponse);
        $response = $push->getResponse();

        $this->assertTrue($response instanceof Response, 'Mapped Response is no instance of Response.');
        $this->assertEquals(Response::class, get_class($response), 'Mapped Response is no instance of Response.');
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
        $response = $push->getResponse();
    }

    /**
     * This test validates all response values to be correct after the mapping process.
     *
     * @test
     */
    public function validMappedProperties()
    {
        $push = new Push($this->xmlCcRegResponse);
        $response = $push->getResponse();

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

        $this->assertEquals('000.100.112', $response->getError()['code']);
        $this->assertEquals(
            'Request successfully processed in \'Merchant in Connector Test Mode\'',
            $response->getError()['message']
        );

        $this->assertTrue($response->isSuccess(), 'Response Status is not success.');
        $this->assertFalse($response->isError(), 'Response Status is not error.');
        $this->assertFalse($response->isPending(), 'Response Status is not pending.');

        $this->assertEquals(
            '209022666cd4706e5f451067592b6be1aff4a913d5bb7f8249f7418ee25c91b3'
            . '18ebac66f41a6692539c8923adfdad6aae26138b1b3a7e37a197ab952be57876',
            $response->getCriterion()->getSecretHash()
        );
    }
}
