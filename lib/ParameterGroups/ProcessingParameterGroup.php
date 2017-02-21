<?php
namespace Heidelpay\PhpApi\ParameterGroups;

/**
 * This class provides the api parameter for the payment response like reason code.
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
class ProcessingParameterGroup extends AbstractParameterGroup
{
    
    /**
     * ProcessingCode
     *
     * @var string code
     */
    public $code = null;
    
    /**
     * ProcessingConfirmationStatus
     *
     * @var sting confirmation status
     */
    public $confirmation_status = null;
    
    /**
     * ProcessingRecoverable
     *
     * @var string recoverable
     */
    public $recoverable = null;
    
    /**
     * ProcessingResult
     *
     * @var string payment transaction result
     */
    public $result = null;
    
    /**
     * ProcessingReasonCode
     *
     * @var string reson_code transaction result
     */
    public $reason_code = null;
    
    /**
     * ProcessingReason
     *
     * @var string reson transaction result
     */
    public $reason = null;
    
    /**
     * ProcessingReturn
     *
     * @var string status message for the shop owner
     */
    public $return = null;
    
    /**
     * ProcessingReturnCode
     *
     * @var string status code for the shop owner
     */
    public $return_code = null;
    
    public $timestamp = null;
    
    /**
     * @var string status message of the transaction
     */
    public $status= null;
    
    /**
     * ProcessingStatusCode
     *
     * @var string status code of the transaction (100.100.100)
     */
    public $status_code = null;
    
    /**
     * Magic setter without property exception
     *
     *  This class has his own setter, because criterions can be used as key value store.
     *  You can use any key and value which is a valid post parameter.
     *
     * @param string $key
     * @param string $value
     *
     * @return \Heidelpay\PhpApi\ParameterGroups\CriterionParameterGroup
     */
    public function set($key, $value)
    {
        $key = strtolower($key);
        $this->$key = $value;
        return $this;
    }
    
    /**
     * ProcessingResult getter
     *
     * @return string result
     */
    public function getResult()
    {
        return $this->result;
    }
    
    /**
     * ProcessingRetrun message getter
     *
     * @return string return
     */
    public function getReturn()
    {
        return $this->return;
    }
    
    /**
     * ProcessingRetrun code getter
     *
     * @return string return code
     */
    public function getReturnCode()
    {
        return $this->return_code;
    }
    
    /**
     * ProcessingStatusCode getter
     *
     * @return string status code
     */
    public function getStatusCode()
    {
        return $this->status_code;
    }
}
