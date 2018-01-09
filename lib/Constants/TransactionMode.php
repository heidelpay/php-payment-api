<?php

namespace Heidelpay\PhpPaymentApi\Constants;

/**
 * Class for Transaction Mode constants
 *
 * @license Use of this software requires acceptance of the License Agreement. See LICENSE file.
 * @copyright Copyright © 2017-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link http://dev.heidelpay.com/php-payment-api
 *
 * @author Stephano Vogel <development@heidelpay.de>
 *
 * @package heidelpay\php-payment-api\constants
 *
 * @since 1.3.0 First time this was introduced.
 */
class TransactionMode
{
    const CONNECTOR_TEST = 'CONNECTOR_TEST';
    const INTEGRATOR_TEST = 'INTEGRATOR_TEST';
    const LIVE = 'LIVE';
}
