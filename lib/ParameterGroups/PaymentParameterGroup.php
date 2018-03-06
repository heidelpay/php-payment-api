<?php

namespace Heidelpay\PhpPaymentApi\ParameterGroups;

/**
 * This class provides the api parameter for payment code
 *
 * The Payment group determines which payment method and type to use and provides all
 * monetary payment details of the transaction.
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
class PaymentParameterGroup extends AbstractParameterGroup
{
    /**
     * PaymentCode
     *
     * This prarameter will be used to set the payment method and type.
     * Notation is for example OT.PA. The first 2 digits are the payment
     * method, in this case online transfer  and the last are the type
     * here preautorisation. Normally the PhpPaymentApi will set the right payment
     * code, but if you want to learn more about this, have a look in Heidelpay
     * whitelable documentation.
     *
     * @var string code (mandatory)
     */
    public $code;

    /**
     * PamyentCode getter
     *
     * @return string code
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * setter for the payment code
     *
     * @param string $code
     *
     * @return \Heidelpay\PhpPaymentApi\ParameterGroups\PaymentParameterGroup
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }
}
