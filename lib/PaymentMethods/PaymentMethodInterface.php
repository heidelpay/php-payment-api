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
 * @package heidelpay\php-payment-api\paymentmethods
 */
interface PaymentMethodInterface extends JsonSerializable
{
    /**
     * Returns the payment code for the payment request.
     *
     * @return string
     */
    public function getPaymentCode();

    /**
     * Returns the brand for the payment method.
     *
     * @return string
     */
    public function getBrand();

    /**
     * Returns a Json representation of itself.
     *
     * @param int $options
     *
     * @return string
     */
    public function toJson($options = 0);
}
