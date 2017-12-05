<?php

namespace Heidelpay\PhpPaymentApi;

use JsonSerializable;

/**
 * Method Interface for Request/Response
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
interface MethodInterface extends JsonSerializable
{
    /**
     * Returns a json representation of itself.
     *
     * @param int $options
     *
     * @return string
     */
    public function toJson($options = 0);
}
