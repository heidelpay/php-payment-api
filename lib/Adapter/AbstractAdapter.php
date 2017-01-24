<?php

namespace Heidelpay\PhpApi\Adapter;

use Heidelpay\PhpApi\Response;
use Heidelpay\PhpApi\Request;

/**
 * Dummy http adapter
 *
 * You can use this class to build your own http apdater.
 *
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  https://dev.heidelpay.de/PhpApi
 *
 * @author  Jens Richter
 *
 * @package  Heidelpay
 * @subpackage PhpApi
 * @category PhpApi
 */
 
abstract class AbstractAdapter
{
    /**
     * send post request to payment server
     * this is the abstract class of the http adapter. Please do not use
     * this class directly. Extend it and create your own curl adapter instead.
     * Otherwise you can use our curl adapter
     *
     * @param $uri string url of the target system
     * @param $post array post payload for a payment request
     *
     * @return array result of the transaction and a instance of the response object
     */
    public function sendPost($uri=null, $post=null)
    {
    }
}
