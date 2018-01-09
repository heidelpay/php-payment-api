<?php

namespace Heidelpay\PhpPaymentApi;

use Heidelpay\PhpPaymentApi\Exceptions\UndefinedXmlResponseException;
use Heidelpay\PhpPaymentApi\Exceptions\XmlResponseParserException;
use Heidelpay\PhpPaymentApi\ParameterGroups\AbstractParameterGroup;
use Heidelpay\PhpPaymentApi\ParameterGroups\AccountParameterGroup;
use Heidelpay\PhpPaymentApi\ParameterGroups\AddressParameterGroup;
use Heidelpay\PhpPaymentApi\ParameterGroups\ConnectorParameterGroup;
use Heidelpay\PhpPaymentApi\ParameterGroups\ContactParameterGroup;
use Heidelpay\PhpPaymentApi\ParameterGroups\FrontendParameterGroup;
use Heidelpay\PhpPaymentApi\ParameterGroups\IdentificationParameterGroup;
use Heidelpay\PhpPaymentApi\ParameterGroups\NameParameterGroup;
use Heidelpay\PhpPaymentApi\ParameterGroups\PaymentParameterGroup;
use Heidelpay\PhpPaymentApi\ParameterGroups\PresentationParameterGroup;
use Heidelpay\PhpPaymentApi\ParameterGroups\ProcessingParameterGroup;
use Heidelpay\PhpPaymentApi\ParameterGroups\TransactionParameterGroup;
use Heidelpay\PhpPaymentApi\PushMapping\Account;
use Heidelpay\PhpPaymentApi\PushMapping\Address;
use Heidelpay\PhpPaymentApi\PushMapping\Connector;
use Heidelpay\PhpPaymentApi\PushMapping\Contact;
use Heidelpay\PhpPaymentApi\PushMapping\Frontend;
use Heidelpay\PhpPaymentApi\PushMapping\Identification;
use Heidelpay\PhpPaymentApi\PushMapping\Name;
use Heidelpay\PhpPaymentApi\PushMapping\Payment;
use Heidelpay\PhpPaymentApi\PushMapping\Presentation;
use Heidelpay\PhpPaymentApi\PushMapping\Processing;
use Heidelpay\PhpPaymentApi\PushMapping\PushMappingInterface;
use Heidelpay\PhpPaymentApi\PushMapping\Transaction;
use SimpleXMLElement;

/**
 * Push XML Mapper
 *
 * Parses heidelpay Push Responses to a PhpPaymentApi Response object.
 *
 * @license    Use of this software requires acceptance of the License Agreement. See LICENSE file.
 * @copyright  Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link      http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author     Stephano Vogel
 *
 * @package    heidelpay
 * @subpackage php-api
 * @category   php-api
 */
class Push
{
    /**
     * The raw XML response
     *
     * @var string
     */
    private $xmlResponse;

    /**
     * The PhpPaymentApi Response object that will be the result
     *
     * @var Response
     */
    private $response;

    /**
     * Mapping array to determine which parameter group
     * has to be mapped by which mapping class
     *
     * @var array
     */
    private $mapping = [
        AccountParameterGroup::class => Account::class,
        AddressParameterGroup::class => Address::class,
        ConnectorParameterGroup::class => Connector::class,
        ContactParameterGroup::class => Contact::class,
        FrontendParameterGroup::class => Frontend::class,
        IdentificationParameterGroup::class => Identification::class,
        NameParameterGroup::class => Name::class,
        PaymentParameterGroup::class => Payment::class,
        PresentationParameterGroup::class => Presentation::class,
        ProcessingParameterGroup::class => Processing::class,
        TransactionParameterGroup::class => Transaction::class
    ];

    /**
     * Push constructor.
     *
     * @param string $xmlResponse a raw string in xml format
     *
     * @throws XmlResponseParserException
     */
    public function __construct($xmlResponse = null)
    {
        if ($xmlResponse !== null && is_string($xmlResponse)) {
            $this->xmlResponse = $xmlResponse;
            $this->parseXmlResponse();
        }
    }

    /**
     * If not setted in contructor, set the raw xml response
     *
     * @param string $response
     */
    public function setRawResponse($response)
    {
        $this->xmlResponse = $response;
    }

    /**
     * Return the Response object
     *
     * If not set, parse the xml response and create the response.
     * Then return it.
     *
     * @return Response|null
     *
     * @throws XmlResponseParserException
     */
    public function getResponse()
    {
        if ($this->response === null) {
            $this->parseXmlResponse();
        }

        return $this->response;
    }

    /**
     * Parses the raw XML response and maps the
     * attributes to a PhpPaymentApi Response.
     *
     * @throws XmlResponseParserException
     */
    private function parseXmlResponse()
    {
        // initiate a new PhpPaymentApi Response.
        $this->response = new Response();

        try {
            if ($this->xmlResponse === null) {
                throw new UndefinedXmlResponseException('XML Response is not set.');
            }

            // get a new SimpleXML objects from the raw response.
            $xml = new SimpleXMLElement($this->xmlResponse);

            // loop though all Response getters
            foreach ($this->getResponseParameterGroups($this->response) as $method) {
                // get the instance of the parameter group by calling it's getter.
                $parameterGroupInstance = call_user_func([$this->response, $method]);

                // get the mapper class
                if ($mapperClassName = $this->getMappingClass($parameterGroupInstance)) {
                    $mapperInstance = new $mapperClassName;
                    $this->setParameterGroupProperties($parameterGroupInstance, $mapperInstance, $xml);
                }
            }

            // set the criterion data
            if (isset($xml->Transaction, $xml->Transaction->Analysis->Criterion)) {
                foreach ($xml->Transaction->Analysis->Criterion as $criterion) {
                    $this->response->getCriterion()->set($criterion['name'], (string)$criterion);
                }
            }
        } catch (\Exception $e) {
            throw new XmlResponseParserException('Problem while parsing the raw xml response: ' . $e->getMessage());
        }
    }

    /**
     * If a Mapping Class for the given parameter group instance is given,
     * return the class name of it.
     *
     * @param $parameterGroupInstance
     *
     * @return string|null
     */
    private function getMappingClass($parameterGroupInstance)
    {
        $className = get_class($parameterGroupInstance);

        return isset($this->mapping[$className]) ? $this->mapping[$className] : null;
    }

    /**
     * Get the ParameterGroup Getters of the Response instance.
     *
     * @param Response $responseInstance
     *
     * @return array
     */
    private function getResponseParameterGroups(Response $responseInstance)
    {
        $methods = [];

        foreach (get_class_methods($responseInstance) as $method) {
            if ($this->isParameterGroupGetter($method)) {
                $methods[] = $method;
            }
        }

        return $methods;
    }

    /**
     * Validates if the given getter is for a ParameterGroup.
     *
     * @param $methodName
     *
     * @return bool
     */
    private function isParameterGroupGetter($methodName)
    {
        return (0 === strpos($methodName, 'get'))
            && ($methodName !== 'getPaymentFormUrl') && ($methodName !== 'getPaymentReferenceId')
            && ($methodName !== 'getError');
    }

    /**
     * Sets the properties of the ParameterGroup instance by mapping
     * the attributes from the Mapping class
     *
     * @param AbstractParameterGroup $parameterGroupInstance
     * @param PushMappingInterface   $mappingClassInstance
     * @param SimpleXMLElement       $xmlResponse
     */
    private function setParameterGroupProperties(
        AbstractParameterGroup $parameterGroupInstance,
        PushMappingInterface $mappingClassInstance,
        SimpleXMLElement $xmlResponse
    ) {
        // set the Response properties according to the mapping properties => xml attributes.
        foreach ($mappingClassInstance->getProperties() as $xmlProperty => $classField) {
            if ($newValue = $mappingClassInstance->getXmlObjectProperty($xmlResponse, $xmlProperty)) {
                $parameterGroupInstance->set($classField, $newValue);
            }
        }

        // set the Response properties according to the mapping fields => xml tags.
        foreach ($mappingClassInstance->getFields() as $xmlField => $classField) {
            if ($newValue = $mappingClassInstance->getXmlObjectField($xmlResponse, $xmlField)) {
                $parameterGroupInstance->set($classField, $newValue);
            }
        }

        // set the Response properties according to the mapping xml field => attributes.
        foreach ($mappingClassInstance->getFieldAttributes() as $xmlProperty => $classField) {
            if ($newValue = $mappingClassInstance->getXmlObjectFieldAttribute($xmlResponse, $xmlProperty)) {
                $parameterGroupInstance->set($classField, $newValue);
            }
        }
    }
}
