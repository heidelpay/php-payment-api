<?php
namespace Heidelpay\PhpApi\ParameterGroups;
use \Heidelpay\PhpApi\ParameterGroups\AbstractParameterGroup;
/**
 * This class provides the api parameter for the payment response like reason code.
 *
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
class ProcessingParameterGroup extends AbstractParameterGroup {
    
    /**
     * ProcessingCode
     * 
     * @var string code
     */
    public $code = NULL;
    
    /**
     * ProcessingConfirmationStatus
     * 
     * @var sting confirmation status
     */
    
    public $confirmation_status = NULL;
    
    /**
     * ProcessingRecoverable
     * @var string recoverable 
     */
    public $recoverable = NULL;
    
    
    /**
     * ProcessingResult
     * @var string payment transaction result 
     */
    public $result = NULL;
    
    /**
     * ProcessingReasonCode
     * @var string reson_code transaction result
     */
    
    public $reason_code = NULL;
    
    /**
     * ProcessingReason
     * @var string reson transaction result
     */
    
    public $reason = NULL;
    
    /**
     * ProcessingReturn
     * @var string status message for the shop owner
     */
    public $return = NULL;
    
    /**
     * ProcessingReturnCode
     * @var string status code for the shop owner
     */
    public $return_code = NULL;
    
    public $timestamp = NULL;
    
    /**
     * @var string status message of the transaction
     */
    public $status= NULL;
    
    /**
     * ProcessingStatusCode
     * @var string status code of the transaction (100.100.100)
     */
    public $status_code = NULL;
    
    /**
     * ProcessingResult getter
     * @return string result
     */
    
    public function getResult() {
        return $this->result;
    }
    
    /**
     * ProcessingRetrun message getter
     * @return string return
     */
    
    public function getReturn() {
        return $this->return;
    }
    
    /**
     * ProcessingRetrun code getter
     * @return string return code
     */
    
    public function getReturnCode() {
        return $this->return_code;
    }
    
    /**
     * ProcessingStatusCode getter
     * @return string status code
     */
    
    public function getStatusCode() {
        return $this->status_code;
    }
    
}