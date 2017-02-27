<?php

namespace Heidelpay\PhpApi\PaymentMethods;

/**
 * This classe is the abstract payment method
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
abstract class AbstractPaymentMethod
{
    use BasicPaymentMethodTrait;

    /**
     * Payment code for this payment method
     *
     * @var string payment code
     */
    protected $_paymentCode = null;
    
    /**
     * Payment brand name for this payment method
     *
     * @var string brand name
     */
    protected $_brand = null;
    
    /**
     * Weather this Payment method can authorise transactions or not
     *
     * @var boolean canAuthorise
     */
    protected $_canAuthorise = false;
    
    /**
     * Weather this Payment method can capture transactions or not
     *
     * @var boolean canCapture
     */
    protected $_canCapture = false;
    
    /**
     * Weather this Payment method can debit transactions or not
     *
     * @var boolean canDebit
     */
    protected $_canDebit = false;
    
    /**
     * Weather this Payment method can refund transactions or not
     *
     * @var boolean canRefund
     */
    protected $_canRefund = false;
    
    /**
     * Weather this Payment method can reversal transactions or not
     *
     * @var boolean canReversal
     */
    protected $_canReversal = false;
    
    /**
     * Weather this Payment method can rebill transactions or not
     *
     * @var boolean canRebill
     */
    protected $_canRebill = false;
    
    /**
     * Weather this Payment method can finalize transactions or not
     *
     * Finalize will be used to tell the Heidelpay system that
     * the order has been shipped out.
     *
     * @var boolean canFinalize
     */
    protected $_canFinalize = false;
    
    /**
     * Weather this Payment method can register account data or not
     *
     * @var boolean canRegistration
     */
    protected $_canRegistration = false;
    
    /**
     * Weather this Payment method can debit on registered account data or not
     *
     * @var boolean canDebitOnRegistration
     */
    protected $_canDebitOnRegistration = false;
    
    /**
     * Weather this Payment method can authorize on registered account data or not
     *
     * @var boolean canAuthorizeOnRegistration
     */
    protected $_canAuthorizeOnRegistration = false;

    /**
     * Payment type authorisation
     *
     * Depending on the payment method this type normally means that the amount
     * of the given account will only be authorized. In case of payment methods
     * like Sofort and Giropay (so called online payments) this type will be
     * used just to get the redirect to their systems.
     *
     * @return \Heidelpay\PhpApi\PaymentMethods\AbstractPaymentMethod|boolean
     */
    public function authorize()
    {
        if ($this->_canAuthorise) {
            $this->getRequest()->getPayment()->set('code', $this->_paymentCode.".PA");
            $this->getRequest()->getCriterion()->set('payment_method', $this->getClassName());
            if ($this->_brand !== null) {
                $this->getRequest()->getAccount()->set('brand', $this->_brand);
            }
        
            $uri = $this->getPaymentUrl();
            $this->_requestArray = $this->getRequest()->convertToArray();
        
            if ($this->_dryRun === false and $uri !== null and is_array($this->_requestArray)) {
                list($this->_responseArray, $this->_response) = $this->getRequest()->send($uri, $this->_requestArray, $this->getAdapter());
            }
        
            return $this;
        }
       
        return false;
    }
    
    /**
     * Payment type authorisation on registration
     *
     * This payment type will be used to make an authorisation on a given registration.
     *
     * @param string payment refernce id (uniqe id of the reqistration)
     * @param mixed $PaymentRefernceId
     *
     * @return \Heidelpay\PhpApi\PaymentMethods\AbstractPaymentMethod|boolean
     */
    public function authorizeOnRegistration($PaymentRefernceId)
    {
        if ($this->_canAuthorizeOnRegistration) {
            $this->getRequest()->getPayment()->set('code', $this->_paymentCode.".PA");
            $this->getRequest()->getCriterion()->set('payment_method', $this->getClassName());
            $this->getRequest()->getFrontend()->set('enabled', 'FALSE');
            $this->getRequest()->getIdentification()->set('referenceId', $PaymentRefernceId);
            $uri = $this->getPaymentUrl();
            $this->_requestArray = $this->getRequest()->convertToArray();
    
            if ($this->_dryRun === false and $uri !== null and is_array($this->_requestArray)) {
                list($this->_responseArray, $this->_response) = $this->getRequest()->send($uri, $this->_requestArray, $this->getAdapter());
            }
    
            return $this;
        }
    
        return false;
    }
    
    /**
     * Payment type capture
     *
     * You can charge a given authorisation by capturing the transaction.
     *
     * @param string payment refernce id ( uniqe id of the authorisation)
     * @param mixed $PaymentRefernceId
     *
     * @return \Heidelpay\PhpApi\PaymentMethods\AbstractPaymentMethod|boolean
     */
    public function capture($PaymentRefernceId)
    {
        if ($this->_canCapture) {
            $this->getRequest()->getPayment()->set('code', $this->_paymentCode.".CP");
            $this->getRequest()->getCriterion()->set('payment_method', $this->getClassName());
            $this->getRequest()->getFrontend()->set('enabled', 'FALSE');
            $this->getRequest()->getIdentification()->set('referenceId', $PaymentRefernceId);
            if ($this->_brand !== null) {
                $this->getRequest()->getAccount()->set('brand', $this->_brand);
            }
        
            $uri = $this->getPaymentUrl();
            $this->_requestArray = $this->getRequest()->convertToArray();
        
            if ($this->_dryRun === false and $uri !== null and is_array($this->_requestArray)) {
                list($this->_responseArray, $this->_response) = $this->getRequest()->send($uri, $this->_requestArray, $this->getAdapter());
            }
        
            return $this;
        }
       
        return false;
    }
    
    /**
     * Payment type debit
     *
     * This payment type will charge the given account directly.
     *
     * @return \Heidelpay\PhpApi\PaymentMethods\AbstractPaymentMethod|boolean
     */
    public function debit()
    {
        if ($this->_canDebit) {
            $this->getRequest()->getPayment()->set('code', $this->_paymentCode.".DB");
            $this->getRequest()->getCriterion()->set('payment_method', $this->getClassName());
            if ($this->_brand !== null) {
                $this->getRequest()->getAccount()->set('brand', $this->_brand);
            }
             
            $uri = $this->getPaymentUrl();
            $this->_requestArray = $this->getRequest()->convertToArray();
        
            if ($this->_dryRun === false and $uri !== null and is_array($this->_requestArray)) {
                list($this->_responseArray, $this->_response) = $this->getRequest()->send($uri, $this->_requestArray, $this->getAdapter());
            }
             
            return $this;
        }
         
        return false;
    }
    
    /**
     * Payment type debit on registration
     *
     * This payment type will charge the given account directly. The debit is
     * related to a registration.
     *
     * @param string payment refernce id ( uniqe id of the reqistration)
     * @param mixed $PaymentRefernceId
     *
     * @return \Heidelpay\PhpApi\PaymentMethods\AbstractPaymentMethod|boolean
     */
    public function debitOnRegistration($PaymentRefernceId)
    {
        if ($this->_canDebitOnRegistration) {
            $this->getRequest()->getPayment()->set('code', $this->_paymentCode.".DB");
            $this->getRequest()->getCriterion()->set('payment_method', $this->getClassName());
            $this->getRequest()->getFrontend()->set('enabled', 'FALSE');
            $this->getRequest()->getIdentification()->set('referenceId', $PaymentRefernceId);
            $uri = $this->getPaymentUrl();
            $this->_requestArray = $this->getRequest()->convertToArray();
             
            if ($this->_dryRun === false and $uri !== null and is_array($this->_requestArray)) {
                list($this->_responseArray, $this->_response) = $this->getRequest()->send($uri, $this->_requestArray, $this->getAdapter());
            }
    
            return $this;
        }
    
        return false;
    }
    
    /**
     * Payment type rebill
     *
     * This payment type will be used to charge a given transaction again. For
     * example, in case of a higher shipping cost. Please make sure that you
     * have the permission of your customer to charge again.
     *
     * @param string payment refernce id ( uniqe id of the debit or capture)
     * @param mixed $PaymentRefernceId
     *
     * @return \Heidelpay\PhpApi\PaymentMethods\AbstractPaymentMethod|boolean
     */
    public function rebill($PaymentRefernceId)
    {
        if ($this->_canRebill) {
            $this->getRequest()->getPayment()->set('code', $this->_paymentCode.".RB");
            $this->getRequest()->getCriterion()->set('payment_method', $this->getClassName());
            $this->getRequest()->getFrontend()->set('enabled', 'FALSE');
            $this->getRequest()->getIdentification()->set('referenceId', $PaymentRefernceId);
            if ($this->_brand !== null) {
                $this->getRequest()->getAccount()->set('brand', $this->_brand);
            }
            
            $uri = $this->getPaymentUrl();
            $this->_requestArray = $this->getRequest()->convertToArray();
    
            if ($this->_dryRun === false and $uri !== null and is_array($this->_requestArray)) {
                list($this->_responseArray, $this->_response) = $this->getRequest()->send($uri, $this->_requestArray, $this->getAdapter());
            }
    
            return $this;
        }
    
        return false;
    }
    
    /**
     * Payment type refund
     *
     * This payment type will be used to give a charge amount or even parts of
     * it back to the given account.
     *
     * @param string payment refernce id ( uniqe id of the debit or capture)
     * @param mixed $PaymentRefernceId
     *
     * @return \Heidelpay\PhpApi\PaymentMethods\AbstractPaymentMethod|boolean
     */
    public function refund($PaymentRefernceId)
    {
        if ($this->_canRefund) {
            $this->getRequest()->getPayment()->set('code', $this->_paymentCode.".RF");
            $this->getRequest()->getCriterion()->set('payment_method', $this->getClassName());
            $this->getRequest()->getFrontend()->set('enabled', 'FALSE');
            $this->getRequest()->getIdentification()->set('referenceId', $PaymentRefernceId);
            if ($this->_brand !== null) {
                $this->getRequest()->getAccount()->set('brand', $this->_brand);
            }
             
            $uri = $this->getPaymentUrl();
            $this->_requestArray = $this->getRequest()->convertToArray();
             
            if ($this->_dryRun === false and $uri !== null and is_array($this->_requestArray)) {
                list($this->_responseArray, $this->_response) = $this->getRequest()->send($uri, $this->_requestArray, $this->getAdapter());
            }
             
            return $this;
        }
         
        return false;
    }
    
    /**
     * Payment type reversal
     *
     * This payment type will be used to give an uncharged amount or even parts of
     * it back to the given account. This can be used to lower an amount on an
     * invoice for example.
     *
     * @param string payment refernce id ( uniqe id of the authorisation)
     * @param mixed $PaymentRefernceId
     *
     * @return \Heidelpay\PhpApi\PaymentMethods\AbstractPaymentMethod|boolean
     */
    public function reversal($PaymentRefernceId)
    {
        if ($this->_canReversal) {
            $this->getRequest()->getPayment()->set('code', $this->_paymentCode.".RV");
            $this->getRequest()->getCriterion()->set('payment_method', $this->getClassName());
            $this->getRequest()->getFrontend()->set('enabled', 'FALSE');
            $this->getRequest()->getIdentification()->set('referenceId', $PaymentRefernceId);
            if ($this->_brand !== null) {
                $this->getRequest()->getAccount()->set('brand', $this->_brand);
            }
    
            $uri = $this->getPaymentUrl();
            $this->_requestArray = $this->getRequest()->convertToArray();
    
            if ($this->_dryRun === false and $uri !== null and is_array($this->_requestArray)) {
                list($this->_responseArray, $this->_response) = $this->getRequest()->send($uri, $this->_requestArray, $this->getAdapter());
            }
    
            return $this;
        }
    
        return false;
    }
    
    /**
     * Payment type registration
     *
     * This payment type will be used to save account data inside the heidelpay
     * system. You will get back a payment reference id. This gives you a way
     * to charge this account later or even to make a recurring payment.
     *
     *
     * @return \Heidelpay\PhpApi\PaymentMethods\AbstractPaymentMethod|boolean
     */
    public function registration()
    {
        if ($this->_canRegistration) {
            $this->getRequest()->getPayment()->set('code', $this->_paymentCode.".RG");
            $this->getRequest()->getCriterion()->set('payment_method', $this->getClassName());
            if ($this->_brand !== null) {
                $this->getRequest()->getAccount()->set('brand', $this->_brand);
            }
    
            $uri = $this->getPaymentUrl();
            $this->_requestArray = $this->getRequest()->convertToArray();
        
            if ($this->_dryRun === false and $uri !== null and is_array($this->_requestArray)) {
                list($this->_responseArray, $this->_response) = $this->getRequest()->send($uri, $this->_requestArray, $this->getAdapter());
            }
    
            return $this;
        }
    
        return false;
    }
}
