<?php

namespace Heidelpay\PhpPaymentApi\Constants;

/**
 * Class for Reason Code constants
 *
 * @license Use of this software requires acceptance of the License Agreement. See LICENSE file.
 * @copyright Copyright © 2017-present heidelpay GmbH. All rights reserved.
 *
 * @link http://dev.heidelpay.com/php-payment-api
 *
 * @author Stephano Vogel <development@heidelpay.com>
 *
 * @package heidelpay\php-payment-api\constants
 *
 * @since 1.3.0 First time this was introduced.
 */
class ReasonCode
{
    const REFERENCE_ERROR = '30';
    const ACCOUNT_VALIDATION = '40';
    const CC_ACCOUNT_VALIDATION = self::ACCOUNT_VALIDATION;
    const BLACKLIST_VALIDATION = '50';
    const ADDRESS_ERROR = '60';
    const COMMUNICATION_ERROR = '70';
    const EXTERNAL_RISK_ERROR = '78';
    const ERROR_3DSECURE = '85';
    const ASYNC_ERROR = '90';
    const AUTHORIZATION_VALIDATION = '95';
}
