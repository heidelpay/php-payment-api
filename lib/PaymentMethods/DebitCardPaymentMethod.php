<?php

namespace Heidelpay\PhpPaymentApi\PaymentMethods;

use Heidelpay\PhpPaymentApi\Constants\PaymentMethod;
use Heidelpay\PhpPaymentApi\TransactionTypes\RegistrationTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\AuthorizeTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\DebitTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\AuthorizeOnRegistrationTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\DebitOnRegistrationTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\RefundTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\ReregistrationTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\ReversalTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\CaptureTransactionType;
use Heidelpay\PhpPaymentApi\TransactionTypes\RebillTransactionType;

/**
 * Debit Card Payment Class
 *
 * This class will be used for every debit card transaction
 *
 * @license    Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright  Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link       http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author     Jens Richter
 *
 * @package heidelpay\php-payment-api\paymentmethods
 */
class DebitCardPaymentMethod implements PaymentMethodInterface
{
    use BasicPaymentMethodTrait;
    use RegistrationTransactionType {
        registration as registrationParent;
    }
    use ReregistrationTransactionType {
        reregistration as reregistrationParent;
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
     * @var string Payment Code for this payment method
     */
    protected $paymentCode = PaymentMethod::DEBIT_CARD;

    /**
     * Payment type authorisation
     * Depending on the payment method this type normally means that the amount
     * of the given account will only be authorized. In case of payment methods
     * like Sofort and Giropay (so called online payments) this type will only be
     * used to get the redirect to their systems.
     * Because of the payment card industry restrictions (Aka pci3), you have
     * to use a payment frame solution to handle the customers credit card information.
     *
     * @param null|mixed $paymentFrameOrigin   uri of your application like http://dev.heidelpay.com
     * @param mixed      $preventAsyncRedirect prevention of redirecting the customer
     * @param null|mixed $cssPath              css url to style the Heidelpay payment frame
     *
     * @throws \Heidelpay\PhpPaymentApi\Exceptions\UndefinedTransactionModeException
     *
     * @return \Heidelpay\PhpPaymentApi\PaymentMethods\DebitCardPaymentMethod
     */
    public function authorize($paymentFrameOrigin = null, $preventAsyncRedirect = 'FALSE', $cssPath = null)
    {
        /**
         * Because of the payment card industriy restictions (Aka pci3) you have
         * to use a payment frame for the input of credit card information
         */
        $this->getRequest()->getFrontend()->setEnabled('TRUE');
        $this->getRequest()->getFrontend()->setPaymentFrameOrigin($paymentFrameOrigin);
        $this->getRequest()->getFrontend()->setPreventAsyncRedirect($preventAsyncRedirect);
        $this->getRequest()->getFrontend()->setCssPath($cssPath);

        return $this->authorizeParent();
    }

    /**
     * Payment type debit
     * This payment type will charge the given account directly.
     * Because of the payment card industry restrictions (Aka pci3), you have
     * to use a payment frame solution to handle the customers credit card information.
     *
     * @param null|mixed $paymentFrameOrigin   uri of your application like http://dev.heidelpay.com
     * @param mixed      $preventAsyncRedirect prevention of redirecting the customer
     * @param null|mixed $cssPath              css url to style the Heidelpay payment frame
     *
     * @throws \Heidelpay\PhpPaymentApi\Exceptions\UndefinedTransactionModeException
     *
     * @return \Heidelpay\PhpPaymentApi\PaymentMethods\DebitCardPaymentMethod
     */
    public function debit($paymentFrameOrigin = null, $preventAsyncRedirect = 'FALSE', $cssPath = null)
    {
        /**
         * Because of the payment card industriy restictions (Aka pci3) you have
         * to use a payment frame for the input of credit card information
         */
        $this->getRequest()->getFrontend()->setPaymentFrameOrigin($paymentFrameOrigin);
        $this->getRequest()->getFrontend()->setPreventAsyncRedirect($preventAsyncRedirect);
        $this->getRequest()->getFrontend()->setCssPath($cssPath);

        return $this->debitParent();
    }

    /**
     * Payment type registration
     * This payment type will be used to save account data inside the heidelpay
     * system. You will get a payment reference id back. This gives you a way
     * to charge this account later or even to make a recurring payment.
     * Because of the payment card industry restrictions (Aka pci3), you have
     * to use a payment frame solution to handle the customers credit card information.
     *
     * @param null|mixed $paymentFrameOrigin   uri of your application like http://dev.heidelpay.com
     * @param mixed      $preventAsyncRedirect prevention of redirecting the customer
     * @param null|mixed $cssPath              css url to style the Heidelpay payment frame
     *
     * @throws \Heidelpay\PhpPaymentApi\Exceptions\UndefinedTransactionModeException
     *
     * @return \Heidelpay\PhpPaymentApi\PaymentMethods\DebitCardPaymentMethod
     */
    public function registration($paymentFrameOrigin = null, $preventAsyncRedirect = 'FALSE', $cssPath = null)
    {
        /**
         * Because of the payment card industriy restictions (Aka pci3) you have
         * to use a payment frame for the input of credit card information
         */
        $this->getRequest()->getFrontend()->setPaymentFrameOrigin($paymentFrameOrigin);
        $this->getRequest()->getFrontend()->setPreventAsyncRedirect($preventAsyncRedirect);
        $this->getRequest()->getFrontend()->setCssPath($cssPath);

        return $this->registrationParent();
    }

    /**
     * This payment type will be used to update account data inside the heidelpay
     * system. The passed reference Id identifies the registration which is to be updated.
     * The reference id is obtained by using the register method first.
     * Because of the payment card industry restrictions (Aka pci3), you have
     * to use a payment frame solution to handle the customers credit card information.
     *
     * @param mixed      $referenceId
     * @param null|mixed $PaymentFrameOrigin   uri of your application like http://dev.heidelpay.com
     * @param mixed      $PreventAsyncRedirect prevention of redirecting the customer
     * @param null|mixed $CssPath              css url to style the Heidelpay payment frame
     *
     * @return ReregistrationTransactionType
     *
     * @throws \Exception
     */
    public function reregistration(
        $referenceId,
        $PaymentFrameOrigin = null,
        $PreventAsyncRedirect = 'FALSE',
        $CssPath = null
    ) {
        $this->getRequest()->getFrontend()->setPaymentFrameOrigin($PaymentFrameOrigin);
        $this->getRequest()->getFrontend()->setPreventAsyncRedirect($PreventAsyncRedirect);
        $this->getRequest()->getFrontend()->setCssPath($CssPath);

        return $this->reregistrationParent($referenceId);
    }
}
