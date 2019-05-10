<?php

namespace Heidelpay\PhpPaymentApi\ParameterGroups;

/**
 * This class provides every api parameter related to the redirect data
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2019-present heidelpay GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-payment-api/
 *
 * @author  Simon Gabriel
 *
 * @package heidelpay\php-payment-api\parameter-groups
 */
class RedirectParameterGroup extends AbstractParameterGroup
{
    /**
     * @var string $url
     */
    public $url;

    /**
     * The parameters to be transferred to the payment api.
     *
     * @var array $parameter
     */
    public $parameter = [];

    /**
     * @param string $name
     * @param $value
     */
    public function addParameter($name, $value)
    {
        $this->parameter[$name] = $value;
    }

    /**
     * @return array
     */
    public function getParameter()
    {
        return $this->parameter;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return RedirectParameterGroup
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }
}
