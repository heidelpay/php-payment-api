<?php
namespace Heidelpay\PhpApi\ParameterGroups;
use \Heidelpay\PhpApi\ParameterGroups\AbstractParameterGroup;

/**
 * This class provides every api parameter used for frontend settings like language etc.
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 * @link  https://dev.heidelpay.de/PhpApi
 * @author  Jens Richter
 *
 * @package  Heidelpay
 * @subpackage PhpApi
 * @category PhpApi
 */

class FrontendParameterGroup extends AbstractParameterGroup {
    
    /**
     *  FrontendCssPath
     * @var string url for a custom css to style the hpf. Only required for hpf
     */
    public $css_path = NULL;
    
    
    /**
     * FrontendEnabled
     * @var boolean enable for async from submit or disable for sync  (mandatory)
     */
    public $enabled = 'TRUE';
    
    /**
     * FrontendLanguage
     * @var string language code ISO 639-1 (mandatory)
     */
    public $language = NULL;
    
    /**
     * FrontendMode
     * @var string always set to withelabel on ngw (mandatory)
     */
    public $mode = "WHITELABEL";
    
    /**
     * FrontendPaymentFrameOrigin
     * @var string origin of your website (like "http://dev.heidelpay.de/"). Only required for hpf
     */
    public $payment_frame_origin = NULL;
    
    /**
     * FrontendPaymentFrameUrl
     * @var string url of the payment iframe, only for credit card and debit card because of pci restrictions
     */
    public $payment_frame_url = NULL;
    /**
     * FrontendPreventAsyncRedirect
     * 
     * Change the behavior of the hpf weather to redirect to the given url or 
     * just give you the response of the payment back to your javascript.
     * Note: Browser side data can not be trusted, so you should check the response
     *  send to your FrontendResponseUrl as well. 
     * @var boolean weather redirect is active or not
     */
    public $prevent_async_redirect = NULL;
    
    /**
     * FrontendRedirectUrl
     * @var string url for whitelabel payment from
     */
    public $redirect_url = NULL;
    
    /**
     * FrontendResponseUrl
     * @var string url of your system for async payment response (mandatory)
     */
    public $response_url = NULL;
    
    /**
     * FrontendEnabled getter
     * @return string enabled
     */
    
    public function getEnabled(){
        return $this->enabled;
    }
    
    /**
     * FrontendLanguage getter
     * @return string language
     */
    
    public function getLanguage(){
        return $this->language;
    }
    
    /**
     * FrontendRedirectUrl getter
     * @return string redirect url
     */
    
    public function getRedirectUrl(){
        return $this->redirect_url;
    }
    
    /**
     * FrontendResponseUrl getter
     * @return string response url
     */
    
    public function getResponseUrl(){
        return $this->response_url;
    }
    
    /**
     * FrontendPaymentFrameOrigin getter
     * @return string payment frame origin
     */
    
    public function getPaymentFrameOrigin(){
        return $this->payment_frame_origin;
        
    }
    
    /**
     * FrontendPaymentFrameUrl getter
     * @return string payment frame url
     */
    
    public function getPaymentFrameUrl(){
        return $this->payment_frame_url;
    
    }
    
    /**
     * FrontendCssPath getter
     * @return string css path
     */
    
    public function getCssPath(){
        return $this->css_path;
    }
    
    /**
     * FrontendReventAsyncRedirect
     * @return boolean weather is enabled or not
     */
    
    public function getPreventAsyncRedirect(){
        return $this->prevent_async_redirect;
    }
    
}