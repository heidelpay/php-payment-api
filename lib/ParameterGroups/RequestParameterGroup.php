<?php

namespace Heidelpay\PhpPaymentApi\ParameterGroups;

/**
 * This class provides sets the request version
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-payment-api/
 *
 * @author  Jens Richter
 *
 * @package heidelpay\php-payment-api\parameter-groups
 */
class RequestParameterGroup extends AbstractParameterGroup
{
    /**
     * RequestVersion
     *
     * @var string version (mandatory)
     */
    public $version = '1.0';

    /**
     * RequestVersion getter
     *
     * @return string request version
     */
    public function getVersion()
    {
        return $this->version;
    }
}
