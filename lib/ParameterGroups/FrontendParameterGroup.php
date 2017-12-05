<?php

namespace Heidelpay\PhpPaymentApi\ParameterGroups;

/**
 * This class provides every api parameter used for frontend settings like language etc.
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
class FrontendParameterGroup extends AbstractParameterGroup
{
    /**
     *  FrontendCssPath
     *
     * @var string url for a custom css to style the hpf. Only required for hpf
     */
    public $css_path;


    /**
     * FrontendEnabled
     *
     * @var boolean enable for async from submit or disable for sync  (mandatory)
     */
    public $enabled = 'TRUE';

    /**
     * FrontendLanguage
     *
     * @var string language code ISO 639-1 (mandatory)
     */
    public $language;

    /**
     * FrontendMode
     *
     * @var string always set to withelabel on ngw (mandatory)
     */
    public $mode = 'WHITELABEL';

    /**
     * FrontendPaymentFrameOrigin
     *
     * @var string origin of your website (like "http://dev.heidelpay.de/"). Only required for hpf
     */
    public $payment_frame_origin;

    /**
     * FrontendPaymentFrameUrl
     *
     * @var string url of the payment iframe, only for credit card and debit card because of pci restrictions
     */
    public $payment_frame_url;
    /**
     * FrontendPreventAsyncRedirect
     *
     * Change the behavior of the hpf weather to redirect to the given url or
     * just give you the response of the payment back to your javascript.
     * Note: Browser side data can not be trusted, so you should check the response
     *  send to your FrontendResponseUrl as well.
     *
     * @var boolean weather redirect is active or not
     */
    public $prevent_async_redirect;

    /**
     * FrontendRedirectUrl
     *
     * @var string url for whitelabel payment from
     */
    public $redirect_url;

    /**
     * FrontendResponseUrl
     *
     * @var string url of your system for async payment response (mandatory)
     */
    public $response_url;

    /**
     * FrontendEnabled getter
     *
     * @return string enabled
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * FrontendLanguage getter
     *
     * @return string language
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Getter for frontend mode
     *
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * FrontendRedirectUrl getter
     *
     * @return string redirect url
     */
    public function getRedirectUrl()
    {
        return $this->redirect_url;
    }

    /**
     * FrontendResponseUrl getter
     *
     * @return string response url
     */
    public function getResponseUrl()
    {
        return $this->response_url;
    }

    /**
     * FrontendPaymentFrameOrigin getter
     *
     * @return string payment frame origin
     */
    public function getPaymentFrameOrigin()
    {
        return $this->payment_frame_origin;
    }

    /**
     * FrontendPaymentFrameUrl getter
     *
     * @return string payment frame url
     */
    public function getPaymentFrameUrl()
    {
        return $this->payment_frame_url;
    }

    /**
     * FrontendCssPath getter
     *
     * @return string css path
     */
    public function getCssPath()
    {
        return $this->css_path;
    }

    /**
     * FrontendReventAsyncRedirect
     *
     * @return boolean weather is enabled or not
     */
    public function getPreventAsyncRedirect()
    {
        return $this->prevent_async_redirect;
    }

    /**
     * Setter used to set a url to a given css file
     *
     * This file can be used to style the heidelpay iframe for
     * credit and debit card. Please have a look into our documentation
     * for the allowed ccs parameter
     *
     * @param string $css_path url to a css file f.e https://dev.heidelpay.de/heidelpay_iframe.css
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\FrontendParameterGroup
     */
    public function setCssPath($css_path)
    {
        $this->css_path = $css_path;
        return $this;
    }

    /**
     * Setter to disable the frontend
     *
     * This setting will force the payment to act in syn mode. This is only possible
     * for transaction that do not need user input. F. e. prepayment, invoice or transactions
     * like debitOnRegistration (only not 3DSecure).
     *
     * @param string $enabled
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\FrontendParameterGroup
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * Setter for the frontend language
     *
     * This setting only influence error messages and the heidelpay payment frame for credit and debit card..
     *
     * @param string $language iso language code 2 letters
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\FrontendParameterGroup
     */
    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    /**
     * Setter for transaction mode
     *
     * @param string $mode
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\FrontendParameterGroup
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
        return $this;
    }

    /**
     * Setter for payment frame origin
     *
     * for the credit and debit card iframe you have to set the source of the javascipt
     * post request. f.e. http://dev.heidelpay.com
     *
     * @param string $payment_frame_origin f.e. http://dev.heidelpay.com
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\FrontendParameterGroup
     */
    public function setPaymentFrameOrigin($payment_frame_origin)
    {
        $this->payment_frame_origin = $payment_frame_origin;
        return $this;
    }

    /**
     * Setter to prevent the iframe to redirect to a give url
     *
     * With this setter you can prevent the payment frame to redirect to an given url. The
     * Frame will give you javascript listen the result of the transaction. This can be used
     * for one step checkout or checkouts like the magento once.
     *
     * @param string $prevent_async_redirect
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\FrontendParameterGroup
     */
    public function setPreventAsyncRedirect($prevent_async_redirect)
    {
        $this->prevent_async_redirect = $prevent_async_redirect;
        return $this;
    }

    /**
     * Setter for the response url of your application
     *
     * The payment server will send the result of the transaction directly to
     * this url in http post notation. Please make sure that this url is reachable
     * form the internet. The response will be send server to server, if you see
     * this url inside your browser, something went wrong. PLease check your php log
     * first, if there is nothing you can identify please write to support@heidelpay.de
     * this the shortid of the transaction or the email address used for the request-
     *
     * @param string $response_url f.e https://dev.heidelpay.de/reponse.php
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\FrontendParameterGroup
     *
     */
    public function setResponseUrl($response_url)
    {
        $this->response_url = $response_url;
        return $this;
    }
}
