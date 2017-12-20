<?php

namespace Heidelpay\PhpPaymentApi\ParameterGroups;

/**
 * This class provides every api parameters for the form configuration
 *
 * configuration will be returned from the api and can not be set.
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author  Jens Richter
 *
 * @package heidelpay\php-payment-api\parameter-groups
 */
class ConfigParameterGroup extends AbstractParameterGroup
{
    /**
     * Supported bank countries for this payment method
     *
     * @var string
     */
    public $bankcountry;

    /**
     * Supported brands countries for this payment method
     *
     * @var string
     */
    public $brands;

    /**
     * optin text for payment methods like santander and easyCredit
     *
     * @var string
     */
    public $optin_text;

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

        if ($result === null) {
            return $this->optin_text;
        }

        return $result;
    }
}
