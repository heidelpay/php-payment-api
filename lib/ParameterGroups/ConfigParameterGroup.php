<?php

namespace Heidelpay\PhpApi\ParameterGroups;

/**
 * This class provides every api parameters for the form configuration
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  https://dev.heidelpay.de/PhpApi
 *
 * @author  Jens Richter
 *
 * @package  Heidelpay
 * @subpackage PhpApi
 * @category PhpApi
 */
class ConfigParameterGroup extends AbstractParameterGroup
{
    /**
     * Supported bank countries for this payment method
     *
     * @var string
     */
    public $bankcountry = null;

    /**
     *  Supported brands countries for this payment method
     *
     * @var string
     */
    public $brands = null;

    /**
     * optin text for special payment methods like santander and easyCredit
     *
     * @var string
     */
    public $optin_text = null;

    /**
     * Config bankcountry getter
     *
     * @return string email
     */
    public function getBankCountry()
    {
        return json_decode($this->bankcountry, true);
    }

    /**
     * Config brands getter
     *
     * @return string brands
     */
    public function getBrands()
    {
        return json_decode($this->brands, true);
    }

    /**
     * Config Option  text getter
     *
     * @return array optin text
     */
    public function getOptinText()
    {
        return json_decode($this->optin_text, true);
    }
}
