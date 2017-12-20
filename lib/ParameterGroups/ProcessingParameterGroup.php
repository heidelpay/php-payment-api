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
 * @package heidelpay\php-payment-api\parameter-groups
 */
class ProcessingParameterGroup extends AbstractParameterGroup
{
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
