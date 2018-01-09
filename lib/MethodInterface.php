<?php

namespace Heidelpay\PhpPaymentApi;

use Heidelpay\PhpPaymentApi\Exceptions\JsonParserException;
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

    /**
     * Takes a JSON representation of an instance and returns
     * a PHP object instance representation of it.
     *
     * @since 1.3.0 First time this was introduced.
     *
     * @param string $json
     *
     * @return AbstractMethod
     *
     * @throws JsonParserException
     */
    public static function fromJson($json);

    /**
     * Takes an array, e.g. a POST response and returns
     * a PHP object instance representation of it.
     *
     * @since 1.3.0 First time this was introduced.
     *
     * @param array $post
     *
     * @return AbstractMethod
     */
    public static function fromPost(array $post);
}
