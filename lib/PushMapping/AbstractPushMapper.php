<?php

namespace Heidelpay\PhpPaymentApi\PushMapping;

/**
 * Abstract Push Mapping Class for Parameter Groups
 *
 * @license Use of this software requires acceptance of the License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link https://dev.heidelpay.de/php-api
 *
 * @author Stephano Vogel
 *
 * @package heidelpay\php-payment-api\push-mapping
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
     * @inheritdoc
     */
    public function getFields()
    {
        return is_array($this->fields) ? $this->fields : [];
    }

    /**
     * @inheritdoc
     */
    public function getFieldAttributes()
    {
        return is_array($this->fieldAttributes) ? $this->fieldAttributes : [];
    }

    /**
     * @inheritdoc
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
