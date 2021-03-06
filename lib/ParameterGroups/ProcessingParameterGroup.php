<?php

namespace Heidelpay\PhpPaymentApi\ParameterGroups;

/**
 * This class provides the api parameter for the payment response like reason code.
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-payment-api/
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
     * @var string reason_code transaction result
     */
    public $reason_code;

    /**
     * @var string reason transaction result
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
     * @var RedirectParameterGroup $redirect
     */
    public $redirect;

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
     * ProcessingReturn message getter
     *
     * @return string return
     */
    public function getReturn()
    {
        return $this->return;
    }

    /**
     * ProcessingReturn code getter
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

    /**
     * @return RedirectParameterGroup
     */
    public function getRedirect()
    {
        if (!$this->redirect instanceof RedirectParameterGroup) {
            $this->redirect = new RedirectParameterGroup();
        }

        return $this->redirect;
    }
}
