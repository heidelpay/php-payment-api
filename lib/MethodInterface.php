<?php

namespace Heidelpay\PhpPaymentApi;

use Heidelpay\PhpPaymentApi\Exceptions\JsonParserException;
use JsonSerializable;

/**
 * Method Interface for Request/Response
 *
 * @license Use of this software requires acceptance of the License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link http://dev.heidelpay.com/heidelpay-php-payment-api/
 *
 * @author Stephano Vogel
 *
 * @package heidelpay/php-payment-api
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
     * Returns an array that represents the object instance.
     *
     * Uses uppercase keys to be compatible with the
     * heidelpay POST Payment API.
     *
     * @param bool $doSort sort the keys in alphabetical order
     *
     * @return array
     */
    public function toArray($doSort);

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
