<?php

namespace Heidelpay\PhpApi;

use Heidelpay\PhpApi\Exceptions\UndefinedXmlResponseException;
use Heidelpay\PhpApi\Exceptions\XmlResponseParserException;
use Heidelpay\PhpApi\ParameterGroups\AbstractParameterGroup;
use Heidelpay\PhpApi\ParameterGroups\AccountParameterGroup;
use Heidelpay\PhpApi\ParameterGroups\AddressParameterGroup;
use Heidelpay\PhpApi\ParameterGroups\ConnectorParameterGroup;
use Heidelpay\PhpApi\ParameterGroups\ContactParameterGroup;
use Heidelpay\PhpApi\ParameterGroups\FrontendParameterGroup;
use Heidelpay\PhpApi\ParameterGroups\IdentificationParameterGroup;
use Heidelpay\PhpApi\ParameterGroups\NameParameterGroup;
use Heidelpay\PhpApi\ParameterGroups\PaymentParameterGroup;
use Heidelpay\PhpApi\ParameterGroups\PresentationParameterGroup;
use Heidelpay\PhpApi\ParameterGroups\ProcessingParameterGroup;
use Heidelpay\PhpApi\ParameterGroups\TransactionParameterGroup;
use Heidelpay\PhpApi\PushMapping\Account;
use Heidelpay\PhpApi\PushMapping\Address;
use Heidelpay\PhpApi\PushMapping\Connector;
use Heidelpay\PhpApi\PushMapping\Contact;
use Heidelpay\PhpApi\PushMapping\Frontend;
use Heidelpay\PhpApi\PushMapping\Identification;
use Heidelpay\PhpApi\PushMapping\Name;
use Heidelpay\PhpApi\PushMapping\Payment;
use Heidelpay\PhpApi\PushMapping\Presentation;
use Heidelpay\PhpApi\PushMapping\Processing;
use Heidelpay\PhpApi\PushMapping\PushMappingInterface;
use Heidelpay\PhpApi\PushMapping\Transaction;
use SimpleXMLElement;

/**
 * Push XML Mapper
 *
 * Parses heidelpay Push Responses to a PhpApi Response object.
 *
 * @license    Use of this software requires acceptance of the License Agreement. See LICENSE file.
 * @copyright  Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link       https://dev.heidelpay.de/php-api
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
     * The PhpApi Response object that will be the result
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
     * attributes to a PhpApi Response.
     */
    private function parseXmlResponse()
    {
        // initiate a new PhpApi Response.
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
            if (isset($xml->Transaction->Analysis->Criterion)) {
                foreach ($xml->Transaction->Analysis->Criterion as $criterion) {
                    $this->response->getCriterion()->set($criterion['name'], (string)$criterion);
                }
            }
        } catch (\Exception $e) {
            if ($e instanceof UndefinedXmlResponseException) {
                throw new XmlResponseParserException('Problem while parsing the raw xml response: ' . $e->getMessage());
            }

            throw new \Exception($e->getMessage());
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
        if ((substr($methodName, 0, 3) == 'get')
            && ($methodName != 'getPaymentFormUrl') && ($methodName != 'getPaymentReferenceId')
            && ($methodName != 'getError')
        ) {
            return true;
        }

        return false;
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
        AbstractParameterGroup &$parameterGroupInstance,
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
