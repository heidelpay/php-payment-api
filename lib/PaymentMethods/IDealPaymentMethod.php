<?php

namespace Heidelpay\PhpApi\PaymentMethods;

/**
 * iDeal Payment Class
 *
 * iDeal is a online payment method in the netherlands.
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
class IDealPaymentMethod extends AbstractPaymentMethod
{
    /**
     * Payment code for this payment method
     *
     * @var string payment code
     */
    protected $_paymentCode = 'OT';

    /**
     * Weather this Payment method can authorise transactions or not
     *
     * @var boolean canAuthorise
     */
    protected $_canAuthorise = true;

    /**
     * Payment brand name for this payment method
     *
     * @var string brand name
     */
    protected $_brand = "IDEAL";
}
