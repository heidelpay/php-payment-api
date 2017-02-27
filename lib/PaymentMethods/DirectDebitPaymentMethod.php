<?php

namespace Heidelpay\PhpApi\PaymentMethods;

/**
 * Direct debit payment Class
 *
 * Direct debit payment method
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
class DirectDebitPaymentMethod extends AbstractPaymentMethod
{
    
    /**
     * Payment code for this payment method
     *
     * @var string payment code
     */
    protected $_paymentCode = 'DD';
    
        /**
     * Weather this Payment method can authorise transactions or not
     *
     * @var boolean canAuthorise
     */
    protected $_canAuthorise = true;
    
    /**
     * Weather this Payment method can capture transactions or not
     *
     * @var boolean canCapture
     */
    protected $_canCapture = true;
    
    /**
     * Weather this Payment method can debit transactions or not
     *
     * @var boolean canDebit
     */
    protected $_canDebit = true;
    
    /**
     * Weather this Payment method can refund transactions or not
     *
     * @var boolean canRefund
     */
    protected $_canRefund = true;
    
    /**
     * Weather this Payment method can reversal transactions or not
     *
     * @var boolean canReversal
     */
    protected $_canReversal = true;
    
    /**
     * Weather this Payment method can rebill transactions or not
     *
     * @var boolean canRebill
     */
    protected $_canRebill = true;
    
    /**
     * Weather this Payment method can register account data or not
     *
     * @var boolean canRegistration
     */
    protected $_canRegistration = true;
    
    /**
     * Weather this Payment method can debit on registered account data or not
     *
     * @var boolean canDebitOnRegistration
     */
    protected $_canDebitOnRegistration = true;
    
    /**
     * Weather this Payment method can authorize on registered account data or not
     *
     * @var boolean canAuthorizeOnRegistration
     */
    protected $_canAuthorizeOnRegistration = true;
}
