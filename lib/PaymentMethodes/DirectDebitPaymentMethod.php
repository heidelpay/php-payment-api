<?php

namespace Heidelpay\PhpApi\PaymentMethodes;
use \Heidelpay\PhpApi\PaymentMethodes\AbstractPaymentMethod as AbstractPaymentMethod;
/**
 * Direct debit payment Class
 *
 * Direct debit payment method
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


class DirectDebitPaymentMethod extends AbstractPaymentMethod {
    
    /**
	 * Payment code for this payment method
	 * @var string payment code
	 */
	
	protected $_paymentCode = 'DD';
	
	    /**
     * Weather this Payment method can authorise transactions or not
     * @var boolean canAuthorise
     */
    
    protected $_canAuthorise = TRUE;
    
    /**
     * Weather this Payment method can capture transactions or not
     * @var boolean canCapture
     */
    
    protected $_canCapture = TRUE;
    
    /**
     * Weather this Payment method can debit transactions or not
     * @var boolean canDebit
     */
    
    protected $_canDebit = TRUE;
    
    /**
     * Weather this Payment method can refund transactions or not
     * @var boolean canRefund
     */
    
    protected $_canRefund = TRUE;
    
    /**
     * Weather this Payment method can reversal transactions or not
     * @var boolean canReversal
     */
    
    protected $_canReversal = TRUE;
    
    /**
     * Weather this Payment method can rebill transactions or not
     * @var boolean canRebill
     */
    
    protected $_canRebill = TRUE;
    
    /**
     * Weather this Payment method can register account data or not
     *
     * @var boolean canRegistration
     */
    
    protected $_canRegistration = TRUE;
    
    /**
     * Weather this Payment method can debit on registered account data or not
     *
     * @var boolean canDebitOnRegistration
     */
    
    protected $_canDebitOnRegistration = TRUE;
    
    /**
     * Weather this Payment method can authorize on registered account data or not
     *
     * @var boolean canAuthorizeOnRegistration
     */
    
    protected $_canAuthorizeOnRegistration = TRUE;
    
}