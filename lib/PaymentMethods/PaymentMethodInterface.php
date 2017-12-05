<?php

namespace Heidelpay\PhpPaymentApi\PaymentMethods;

use JsonSerializable;

/**
 * Interface for payment methods
 *
 * @license Use of this software requires acceptance of the License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author Stephano Vogel
 *
 * @package heidelpay/php-api
 */
interface PaymentMethodInterface extends JsonSerializable
{
    /**
     * Returns a Json representation of itself.
     *
     * @param int $options
     *
     * @return string
     */
    public function toJson($options = 0);
}
