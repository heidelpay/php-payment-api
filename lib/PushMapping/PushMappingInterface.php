<?php

namespace Heidelpay\PhpPaymentApi\PushMapping;

use SimpleXMLElement;

/**
 * Interface for Push Mapping classes
 *
 * @license Use of this software requires acceptance of the License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link http://dev.heidelpay.com/php-payment-api
 *
 * @author Stephano Vogel
 *
 * @package heidelpay\php-payment-api\push-mapping
 */
interface PushMappingInterface
{
    /**
     * Returns the field properties of the mapping class.
     *
     * @return array
     */
    public function getFields();

    /**
     * Returns an array of field attributes.
     *
     * @return array
     */
    public function getFieldAttributes();

    /**
     * Returns the properties of the mapping class.
     *
     * @return array
     */
    public function getProperties();

    /**
     * Method to retrieve the mapped SimpleXMLElement tag content.
     *
     * @param SimpleXMLElement $xmlElement
     * @param string           $field
     *
     * @return string|null
     */
    public function getXmlObjectField(SimpleXMLElement $xmlElement, $field);

    /**
     * Method to retrieve a mapped SimpleXMLElement field attribute.
     *
     * @param SimpleXMLElement $xmlElement
     * @param string           $field
     *
     * @return string|null
     */
    public function getXmlObjectFieldAttribute(SimpleXMLElement $xmlElement, $field);

    /**
     * Method to retrieve the mapped SimpleXMLElement tag property.
     *
     * @param SimpleXMLElement $xmlElement
     * @param string           $property
     *
     * @return string|null
     */
    public function getXmlObjectProperty(SimpleXMLElement $xmlElement, $property);
}
