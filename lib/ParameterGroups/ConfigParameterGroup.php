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
     * Supported brands countries for this payment method
     *
     * @var string
     */
    public $brands = null;

    /**
     * optin text for payment methods like santander and easyCredit
     *
     * @var string
     */
    public $optin_text = null;

    /**
     * Config bankcountry getter
     *
     * @return string
     */
    public function getBankCountry()
    {
        return json_decode($this->bankcountry, true);
    }

    /**
     * Config brands getter
     *
     * @return string
     */
    public function getBrands()
    {
        return json_decode($this->brands, true);
    }

    /**
     * Config Optin-text getter
     *
     * @return array|string
     */
    public function getOptinText()
    {
        $result = json_decode($this->optin_text, true);

        if (empty($result)) {
            return $this->optin_text;
        }

        return $result;
    }
}
