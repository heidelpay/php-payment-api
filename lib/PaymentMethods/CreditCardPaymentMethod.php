<?php

namespace Heidelpay\PhpPaymentApi\PaymentMethods;

use Heidelpay\PhpPaymentApi\TransactionTypes\RegistrationTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\AuthorizeTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\DebitTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\AuthorizeOnRegistrationTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\DebitOnRegistrationTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\RefundTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\ReversalTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\CaptureTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\RebillTransactionType;

/**
 * Credit Card Payment Class
 *
 * This class will be used for every credit card transaction
 *
 * @license    Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright  Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link       http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author     Jens Richter
 *
 * @package    Heidelpay
 * @subpackage PhpPaymentApi
 * @category   PhpPaymentApi
 */
class CreditCardPaymentMethod
{
    use BasicPaymentMethodTrait;
    use RegistrationTransactionType {
        registration as registrationParent;
    }
    use AuthorizeTransactionType {
        authorize as authorizeParent;
    }
    use DebitTransactionType {
        debit as debitParent;
    }
    use AuthorizeOnRegistrationTransactionType;
    use DebitOnRegistrationTransactionType;
    use RefundTransactionType;
    use ReversalTransactionType;
    use CaptureTransactionType;
    use RebillTransactionType;

    /**
     * Payment code for this payment method
     *
     * @var string payment code
     */
    protected $_paymentCode = 'CC';

    /**
     * Payment brand name for this payment method
     *
     * @var string brand name
     */
    protected $_brand;

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
     * @param null|mixed $PaymentFrameOrigin   uri of your application like http://dev.heidelpay.com
     * @param mixed      $PreventAsyncRedirect - prevention of redirecting the customer
     * @param null|mixed $CssPath              css url to style the Heidelpay payment frame
     *
     * @throws \Exception
     *
     * @return \Heidelpay\PhpPaymentApi\PaymentMethods\CreditCardPaymentMethod
     */
    public function authorize($PaymentFrameOrigin = null, $PreventAsyncRedirect = 'FALSE', $CssPath = null)
    {
        $this->getRequest()->getFrontend()->set('enabled', 'TRUE');
        $this->getRequest()->getFrontend()->set('payment_frame_origin', $PaymentFrameOrigin);
        $this->getRequest()->getFrontend()->set('prevent_async_redirect', $PreventAsyncRedirect);
        $this->getRequest()->getFrontend()->set('css_path', $CssPath);

        return $this->authorizeParent();
    }

    /**
     * Payment type debit
     *
     * This payment type will charge the given account directly.
     * Because of payment card industry restrictions (Aka pci3), you have
     * to use a payment frame solution to handle the customers credit card information.
     *
     * @param null|mixed $PaymentFrameOrigin   uri of your application like http://dev.heidelpay.com
     * @param mixed      $PreventAsyncRedirect prevention of redirecting the customer
     * @param null|mixed $CssPath              css url to style the Heidelpay payment frame
     *
     * @throws \Exception
     *
     * @return \Heidelpay\PhpPaymentApi\PaymentMethods\CreditCardPaymentMethod|boolean
     */
    public function debit($PaymentFrameOrigin = null, $PreventAsyncRedirect = 'FALSE', $CssPath = null)
    {
        $this->getRequest()->getFrontend()->set('payment_frame_origin', $PaymentFrameOrigin);
        $this->getRequest()->getFrontend()->set('prevent_async_redirect', $PreventAsyncRedirect);
        $this->getRequest()->getFrontend()->set('css_path', $CssPath);

        return $this->debitParent();
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
     * @param null|mixed $PaymentFrameOrigin   uri of your application like http://dev.heidelpay.com
     * @param mixed      $PreventAsyncRedirect prevention of redirecting the customer
     * @param null|mixed $CssPath              css url to style the Heidelpay payment frame
     *
     * @throws \Exception
     *
     * @return \Heidelpay\PhpPaymentApi\PaymentMethods\CreditCardPaymentMethod|boolean
     */
    public function registration($PaymentFrameOrigin = null, $PreventAsyncRedirect = 'FALSE', $CssPath = null)
    {
        $this->getRequest()->getFrontend()->set('payment_frame_origin', $PaymentFrameOrigin);
        $this->getRequest()->getFrontend()->set('prevent_async_redirect', $PreventAsyncRedirect);
        $this->getRequest()->getFrontend()->set('css_path', $CssPath);

        return $this->registrationParent();
    }
}
