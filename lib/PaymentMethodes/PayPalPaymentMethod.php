<?php

namespace Heidelpay\PhpApi\PaymentMethodes;
use \Heidelpay\PhpApi\PaymentMethodes\AbstractPaymentMethod as AbstractPaymentMethod;
/**
 * PayPal Payment Class
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


class PayPalPaymentMethod extends AbstractPaymentMethod {
    
    /**
	 * Payment code for this payment method
	 * @var string payment code
	 */
	
	protected $_paymentCode = 'VA';
	
	/**
	 * Weather this Payment method can authorise transactions or not
	 * @var boolean canAuthorise
	 */
	
	protected $_canAuthorise = TRUE;
	
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
     * Payment brand name for this payment method
     * @var string brand name
     */
    
    protected $_brand = "PAYPAL";
    
}