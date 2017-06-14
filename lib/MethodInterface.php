<?php

namespace Heidelpay\PhpApi;

use JsonSerializable;

/**
 * Method Interface
 * @license Use of this software requires acceptance of the License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 * @link https://dev.heidelpay.de/php-api
 * @author Stephano Vogel
 * @package heidelpay/php-api
 */
interface MethodInterface extends JsonSerializable
{
    /**
     * Returns a json representation of itself.
     *
     * @return string
     */
    public function toJson();
}
