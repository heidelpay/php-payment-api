<?php

namespace Heidelpay\PhpPaymentApi\ParameterGroups;

/**
 * This class provides the api parameter for the payment response like reason code.
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
class ProcessingParameterGroup extends AbstractParameterGroup
{
    const RESULT_ACK = 'ACK';
    const RESULT_NOK = 'NOK';

    const STATUS_CODE_SUCCESS = '00';
    const STATUS_CODE_NEUTRAL = '40';
    const STATUS_CODE_WAITING_BANK = '59';
    const STATUS_CODE_REJECTED_BANK = '60';
    const STATUS_CODE_REJECTED_RISK = '65';
    const STATUS_CODE_REJECTED_VALIDATION = '70';
    const STATUS_CODE_WAITING = '80';
    const STATUS_CODE_NEW = '90';

    const REASON_CODE_REFERENCE_ERROR = '30';
    const REASON_CODE_ACCOUNT_VALIDATION = '40';
    const REASON_CODE_CC_ACCOUNT_VALIDATION = self::REASON_CODE_ACCOUNT_VALIDATION;
    const REASON_CODE_BLACKLIST_VALIDATION = '50';
    const REASON_CODE_ADDRESS_ERROR = '60';
    const REASON_CODE_COMMUNICATION_ERROR = '70';
    const REASON_CODE_EXTERNAL_RISK_ERROR = '78';
    const REASON_CODE_3DSECURE_ERROR = '85';
    const REASON_CODE_ASYNC_ERROR = '90';
    const REASON_CODE_AUTHORIZATION_VALIDATION = '95';

    /**
     * @var string code
     */
    public $code;

    /**
     * @var string confirmation status
     */
    public $confirmation_status;

    /**
     * @var string recoverable
     */
    public $recoverable;

    /**
     * @var string payment transaction result
     */
    public $result;

    /**
     * @var string reson_code transaction result
     */
    public $reason_code;

    /**
     * @var string reson transaction result
     */
    public $reason;

    /**
     * @var string status message for the shop owner
     */
    public $return;

    /**
     * @var string status code for the shop owner
     */
    public $return_code;

    /**
     * @var string Timestamp of the processing date and time
     */
    public $timestamp;

    /**
     * @var string status message of the transaction
     */
    public $status;

    /**
     * @var string status code of the transaction (100.100.100)
     */
    public $status_code;

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
