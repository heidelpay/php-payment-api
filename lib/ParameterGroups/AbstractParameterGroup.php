<?php

namespace Heidelpay\PhpPaymentApi\ParameterGroups;

/**
 * The AbstractParameterGroup provides functions for every parameter group which extends this class
 *
 * @license    Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright  Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link       http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author     Jens Richter
 *
 * @package    Heidelpay
 * @subpackage PhpPaymentApi
 * @category   PhpPaymentApi
 */
abstract class AbstractParameterGroup implements ParameterGroupInterface
{
    /**
     * Return the name of the used class
     *
     * @return string class name
     */
    public static function getClassName()
    {
        return get_called_class();
    }

    /**
     * Magic setter
     *
     * @param string $key
     * @param string $value
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\AbstractParameterGroup
     */
    public function set($key, $value)
    {
        $key = strtolower($key);

        $this->$key = $value;
        return $this;
    }

    /**
     * Returns an array for a json representation.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $return = [];
        foreach (get_object_vars($this) as $field => $value) {
            $return[$field] = $value;
        }

        return $return;
    }

    /**
     * @inheritdoc
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }
}
