<?php

namespace Heidelpay\PhpPaymentApi\Adapter;

use Heidelpay\PhpPaymentApi\Response;

/**
 * Standard curl adapter
 *
 * You can use this adapter for your project or you can
 * create one based on a standard library like zend-http
 * or guzzlehttp.
 *
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author  Jens Richter
 *
 * @package  Heidelpay
 * @subpackage PhpPaymentApi
 * @category PhpPaymentApi
 */
class CurlAdapter implements HttpAdapterInterface
{
    /**
     * send post request to payment server
     *
     * @param $uri string url of the target system
     * @param $post array post payload for a payment request
     *
     * @return array result of the transaction and a instance of the response object
     */
    public function sendPost($uri = null, $post = null)
    {
        $request = $result = null;
        $response = $error = $info = array();

        if (!extension_loaded('curl')) {
            $result = array(
                'PROCESSING_RESULT' => 'NOK',
                'PROCESSING_RETURN' => 'Connection error php-curl not installed',
                'PROCESSING_RETURN_CODE' => 'CON.ERR.CUR'
            );
            return array($result, new Response($result));
        }


        $request = curl_init();
        curl_setopt($request, CURLOPT_URL, $uri);
        curl_setopt($request, CURLOPT_HEADER, 0);
        curl_setopt($request, CURLOPT_FAILONERROR, true);
        curl_setopt($request, CURLOPT_TIMEOUT, 60);
        curl_setopt($request, CURLOPT_CONNECTTIMEOUT, 60);

        if (is_array($post)) {
            curl_setopt($request, CURLOPT_POST, true);
            curl_setopt($request, CURLOPT_POSTFIELDS, http_build_query($post));
        }

        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($request, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($request, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($request, CURLOPT_SSLVERSION, 6);       // CURL_SSLVERSION_TLSv1_2
        curl_setopt($request, CURLOPT_USERAGENT, 'PhpPaymentApi');

        $response = curl_exec($request);
        $error = curl_error($request);
        $info = curl_getinfo($request, CURLINFO_HTTP_CODE);

        curl_close($request);

        if (isset($error) and !empty($error)) {
            $errorCode =
                (is_array($info) && array_key_exists('CURLINFO_HTTP_CODE', $info))
                    ? $info['CURLINFO_HTTP_CODE']
                    : 'DEF';

            $result = array(
                'PROCESSING_RESULT' => 'NOK',
                'PROCESSING_RETURN' => 'Connection error http status ' . $error,
                'PROCESSING_RETURN_CODE' => 'CON.ERR.' . $errorCode
            );
        }

        if (empty($error) && is_string($response)) {
            parse_str($response, $result);
        }

        return array($result, new Response($result));
    }
}
