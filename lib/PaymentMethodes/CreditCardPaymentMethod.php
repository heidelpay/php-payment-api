<?php
namespace Heidelpay\PhpApi\PaymentMethodes;

use \Heidelpay\PhpApi\PaymentMethodes\AbstractPaymentMethod as AbstractPaymentMethod;
use Heidelpay\Tests\PhpApi\Unit\PaymentMethodes\DebitCardPaymentMerhodTest;
use Symfony\Component\Validator\Constraints\NotBlank;
/**
 * Credit Card Payment Class
 * 
 * This class will be used for every credit card transaction
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

class CreditCardPaymentMethod extends AbstractPaymentMethod
{
    
    /**
     * Payment code for this payment method
     * @var string payment code
     */
    
    protected $_paymentCode = 'CC';
    
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
    
    /**
     * Payment type authorisation
     *
     * Depending on the payment method this type normally means that the amount
     * of the given account will only be authorized. In case of payment methods
     * like Sofort and Giropay (so called online payments) this type will only be
     * used to get the redirect to their systems.
     * Because of payment card industry restrictions (Aka pci3), you have
     * to use a payment frame solution to handle the customers credit card information.
     * 
     * @param string PaymentFrameOrigin - uri of your application like https://dev.heidelpay.de
     * @param boolean PreventAsyncRedirect - this will tell the payment weather it should redirect the customer or not  
     * @param string CSSPath - css url to style the Heidelpay payment frame  
     *
     * @return \Heidelpay\PhpApi\PaymentMethodes\CreditCardPaymentMethod|boolean
     */

    public function authorize($PaymentFrameOrigin = NULL, $PreventAsyncRedirect = "FALSE", $CssPath = NULL)
    {
        if ($this->_canAuthorise) {
            
            $this->getRequest()->getFrontend()->set('payment_frame_origin',$PaymentFrameOrigin);
            $this->getRequest()->getFrontend()->set('prevent_async_redirect',$PreventAsyncRedirect);
            $this->getRequest()->getFrontend()->set('css_path',$CssPath);
            
            return parent::authorize();
        }
    }
    
    /**
     * Payment type debit
     *
     * This payment type will charge the given account directly.
     * Because of payment card industry restrictions (Aka pci3), you have
     * to use a payment frame solution to handle the customers credit card information.
     *
     * @param string PaymentFrameOrigin - uri of your application like https://dev.heidelpay.de
     * @param boolean PreventAsyncRedirect - this will tell the payment weather it should redirect the customer or not  
     * @param string CSSPath - css url to style the Heidelpay payment frame
     * 
     * @return \Heidelpay\PhpApi\PaymentMethodes\CreditCardPaymentMethod|boolean
     */
    
    public function debit($PaymentFrameOrigin = NULL, $PreventAsyncRedirect = "FALSE", $CssPath = NULL)
    {
        if ($this->_canDebit) {
    
            $this->getRequest()->getFrontend()->set('payment_frame_origin',$PaymentFrameOrigin);
            $this->getRequest()->getFrontend()->set('prevent_async_redirect',$PreventAsyncRedirect);
            $this->getRequest()->getFrontend()->set('css_path',$CssPath);
    
            return parent::debit();
        }
    }
    
    /**
     * Payment type registration
     *
     * This payment type will be used to save account data inside the heidelpay
     * system. You will get a payment reference id back. This provides you a way
     * to charge this account later or even to make a recurring payment.
     * Because of the payment card industry restrictions (Aka pci3), you have
     * to use a payment frame solution to handle the customers credit card information.
     * 
     * @param string PaymentFrameOrigin - uri of your application like https://dev.heidelpay.de
     * @param boolean PreventAsyncRedirect - this will tell the payment weather it should redirect the customer or not  
     * @param string CSSPath - css url to style the Heidelpay payment frame        
     *
     * @return \Heidelpay\PhpApi\PaymentMethodes\CreditCardPaymentMethod|boolean
     */
    public function registration($PaymentFrameOrigin = NULL, $PreventAsyncRedirect = "FALSE", $CssPath = NULL)
    {
        if ($this->_canRegistration) {
            /**
             */
    
            $this->getRequest()->getFrontend()->set('payment_frame_origin',$PaymentFrameOrigin);
            $this->getRequest()->getFrontend()->set('prevent_async_redirect',$PreventAsyncRedirect);
            $this->getRequest()->getFrontend()->set('css_path',$CssPath);
    
            return parent::registration();
        }
    }
}