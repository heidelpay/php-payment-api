<?php
/**
 * Http adapter interface
 *
 * Http adapters to be used by this api should implement this interface.
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-payment-api/
 *
 * @author  Simon Gabriel <simon.gabriel@heidelpay.com>
 *
 * @package  Heidelpay
 * @subpackage PhpPaymentApi
 * @category PhpPaymentApi
 */
namespace Heidelpay\PhpPaymentApi\Adapter;

/**
 * Http adapters to be used by this api should implement this interface.
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-payment-api/
 *
 * @author  Simon Gabriel
 *
 * @package  Heidelpay
 * @subpackage PhpPaymentApi
 * @category PhpPaymentApi
 */
interface HttpAdapterInterface
{
    /**
     * send post request to payment server
     *
     * @param $uri string url of the target system
     * @param $post array post payload for a payment request
     *
     * @return array result of the transaction and a instance of the response object
     */
    public function sendPost($uri = null, $post = null);
}
