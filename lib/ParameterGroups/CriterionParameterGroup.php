<?php

namespace Heidelpay\PhpApi\ParameterGroups;

/**
 * This class provides a key value store for api parameter
 *
 * All parameter that start with Criterion will be given to the payment api and
 * send back in return. This class also provides some special parameter like
 * §secret and $sdk_name for instance.
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author  Jens Richter
 *
 * @package  Heidelpay
 * @subpackage PhpApi
 * @category PhpApi
 */
class CriterionParameterGroup extends AbstractParameterGroup
{
    /**
     * Currently used payment methode
     *
     * @var string payment methode
     */
    public $payment_method;

    /**
     * hash to verify the response
     *
     * @var string hash to verify the response
     */
    public $secret;

    /**
     * Sdk name
     *
     * @var string sdk name
     */
    public $sdk_name = 'Heidelpay\PhpApi';

    /**
     * Sdk version
     *
     * @var string version
     */
    public $sdk_version = '17.9.27';

    /**
     * CriterionPaymentMethod getter
     *
     * @return string payment method
     */
    public function getPaymentMethod()
    {
        return $this->payment_method;
    }

    /**
     * CriterionSecret setter
     *
     * @var string identificaton transaction id
     * @var string secret of your application
     *
     * @return \Heidelpay\PhpApi\ParameterGroups\CriterionParameterGroup
     *
     * @param mixed $value
     * @param mixed $secret
     */
    public function setSecret($value, $secret)
    {
        $this->secret = hash('sha512', $value . $secret);
        return $this;
    }

    /**
     * CriterionSecret getter
     *
     * @return string secret
     */
    public function getSecretHash()
    {
        return $this->secret;
    }

    /**
     * getter for sdk_version
     *
     * @return string sdk version
     */
    public function getSdkVersion()
    {
        return $this->sdk_version;
    }

    /**
     * getter for sdk_name
     *
     * @return string sdk version
     */
    public function getSdkName()
    {
        return $this->sdk_name;
    }
}
