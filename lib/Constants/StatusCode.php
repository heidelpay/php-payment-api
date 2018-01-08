<?php

namespace Heidelpay\PhpPaymentApi\Constants;

/**
 * Class for Status Code constants
 *
 * @license Use of this software requires acceptance of the License Agreement. See LICENSE file.
 * @copyright Copyright © 2017-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link http://dev.heidelpay.com/php-payment-api
 *
 * @author Stephano Vogel <development@heidelpay.de>
 *
 * @package heidelpay\php-payment-api\constants
 */
class StatusCode
{
    const SUCCESS = '00';
    const NEUTRAL = '40';
    const WAITING_BANK = '59';
    const REJECTED_BANK = '60';
    const REJECTED_RISK = '65';
    const REJECTED_VALIDATION = '70';
    const WAITING = '80';
    const NEW_TRANSACTION = '90';
}
