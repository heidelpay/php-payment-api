<?php

namespace Heidelpay\PhpApi\PushMapping;

/**
 * Summary
 *
 * @license Use of this software requires acceptance of the License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link https://dev.heidelpay.de/php-api
 *
 * @author Stephano Vogel
 *
 * @package heidelpay
 * @subpackage php-api
 * @category php-api
 */
abstract class AbstractPushMapper implements PushMappingInterface
{
    /**
     * The XML tags that will be mapped, e.g. the <bank> property inside of <account>
     * Syntax follows: 'XML Tag Name' => 'Class Field Name'
     *
     * @var array
     */
    public $fields = [];

    /**
     * The XML Field Attributes that will be mapped, e.g. <field attribute="">
     * Syntax follows: 'Field:Attribute' => 'Class Field Name'
     *
     * @var array
     */
    public $fieldAttributes = [];

    /**
     * The XML properties that will be mapped, e.g. the 'name' property in <account name="">
     * Syntax follows: 'XML Property Name' => 'Class Field Name'
     *
     * @var array
     */
    public $properties = [];

    /**
     * @param string $fieldName
     * @return string|null
     */
    public function getField($fieldName)
    {
        return isset($this->fields[$fieldName]) ? $this->fields[$fieldName] : null;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return is_array($this->fields) ? $this->fields : [];
    }

    public function getFieldAttributes()
    {
        return is_array($this->fieldAttributes) ? $this->fieldAttributes : [];
    }

    /**
     * @param string $propertyName
     * @return array
     */
    public function getProperty($propertyName)
    {
        return isset($this->properties[$propertyName]) ? $this->properties[$propertyName] : null;
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return is_array($this->properties) ? $this->properties : [];
    }

    /**
     * @inheritdoc
     */
    public function getXmlObjectField(\SimpleXMLElement $xmlElement, $field)
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getXmlObjectFieldAttribute(\SimpleXMLElement $xmlElement, $field)
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getXmlObjectProperty(\SimpleXMLElement $xmlElement, $property)
    {
        return null;
    }
}
