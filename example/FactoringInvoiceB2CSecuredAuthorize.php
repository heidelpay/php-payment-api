<?php
namespace Heidelpay\Example\PhpPaymentApi;

/**
 * Invoice b2c secured authorize example with factoring
 *
 * This is a coding example for invoice b2c secured authorize using heidelpay php-payment-api
 * extension.
 *
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-payment-api/
 *
 * @author  David Owusu
 *
 * @category example
 */

/**
 * For security reason all examples are disabled by default.
 */
use Heidelpay\PhpPaymentApi\PaymentMethods\InvoiceB2CSecuredPaymentMethod;

require_once './_enableExamples.php';
if (defined('HEIDELPAY_PHP_PAYMENT_API_EXAMPLES') and HEIDELPAY_PHP_PAYMENT_API_EXAMPLES !== true) {
    exit();
}

//#######   Load EasyCredit constants.
require_once __DIR__ . '/FactoringInvoice/FactoringInvoiceConstants.php';

//#######   Checks whether the response file is writable. ##############################################################
//#######   The results from the easyCredit payment plan selection will be sent via POST in a server-to-server request.#
//#######   Normally the information will be stored within the database to make them accessable in the customer session#
//#######   in order to safe the information in this example we use the file defined in RESPONSE_FILE_NAME constant.   #
//#######   Only necessary for this example.                                                                           #
if (!is_writable(RESPONSE_FILE_NAME)) {
    echo '<h1>Factoring Examle example</h1>';
    echo 'File: ' . RESPONSE_FILE_NAME . ' is not writable or does not exist. Please change permissions.';
    exit;
}

/**
 * Require the composer autoloader file
 */
require_once __DIR__ . '/../../../autoload.php';

//####### 1. Create an instance of the InvoiceB2CSecured payment method. ###############################################
/**
 * Load a new instance of the payment method
 */
$invoice = new InvoiceB2CSecuredPaymentMethod();


//####### 2. Prepare and send an initialization request. ######################################################
//#######    The response will provide a redirectUrl to where the customer can be redirected to. #

/**
 * Set up your authentification data for Heidepay api. The Constants are defined in FactoringInvoiceConstants.php
 *
 * @link https://dev.heidelpay.com/testumgebung/#Authentifizierungsdaten
 */
$invoice->getRequest()->authentification(
    HEIDELPAY_SECURITY_SENDER,  // SecuritySender
    HEIDELPAY_USER_LOGIN,  // UserLogin
    HEIDELPAY_USER_PASSWORD,  // UserPassword
    HEIDELPAY_TRANSACTION_CHANNEL,  // TransactionChannel credit card without 3d secure
    true                                 // Enable sandbox mode
);
/**
 * Set up asynchronous request parameters
 */
$invoice->getRequest()->async(
    'EN', // Language code for the Frame
    RESPONSE_URL  // Response url from your application
);

/**
 * Set up customer information required for risk checks
 */
$invoice->getRequest()->customerAddress(
    'Heidel',                  // Given name
    'Berger-Payment',           // Family name
    null,                     // Company Name
    '12344',                   // Customer id of your application
    'Vagerowstr. 18',          // Billing address street
    'DE-BW',                   // Billing address state
    '69115',                   // Billing address post code
    'Heidelberg',              // Billing address city
    'DE',                      // Billing address country code
    'support@heidelpay.com'     // Customer mail address
);

/********************************************************************************************************************'''
 * ###### Call factoring function to set necessary parameters.
 * ###### Second parameter $shopperId is not needed here since it is already set with function call customerAddress().
 * ###### If not you can call the factoring() as you you can see below in the
 * ###### commend.
 *
 * The InvoiceID has to be unique for the merchant.
 */
$invoice->getRequest()->factoring('iv' . date('YmdHis'));
//$invoice->getRequest()->factoring('iv' . date('YmdHis'), '1234');

/**
 * Set up basket or transaction information
 */
$invoice->getRequest()->basketData(
    '2843294932', // Reference Id of your application
    23.12,                         // Amount of this request
    'EUR',                         // Currency code of this request
    '39542395235ßfsokkspreipsr'    // A secret passphrase from your application
);

/**
 * For secured payment methods it is necessary to add additional customer
 * information. For a better exception rate please also provide the basket details
 * by using the basket api.
 */
$invoice->getRequest()->b2cSecured(
    'MR',  // Customer salutation
    '1982-07-12', // Customer birth date
    null // Basket api id
);


/**
 * Set necessary parameters for heidelpay payment and send the request
 */
$invoice->authorize();
?>
<html>
<head>
    <title>Invoice authorize with factoring example</title>
</head>
<body>
<?php
//####### 3. Display redirect URL.
if ($invoice->getResponse()->isSuccess()) {
    echo '<a href="' . $invoice->getResponse()->getPaymentFormUrl() . '">place Invoice</a>';
} else {
    echo '<pre>' . print_r($invoice->getResponse()->getError(), 1) . '</pre>';
}
?>
<p>It is not necessary to show the redirect url to your customer. You can
    use php header to forward your customer directly.<br/>
    For example:<br/>
    header('Location: '.$Invoice->getResponse()->getPaymentFromUrl());
</p>
<p>Note: If you can not provide the customer details for secured invoice form your
    application. You can build a form using getPaymentFormUrl() and provide NAME.SALUTATION
    and NAME.BIRTHDATE as input fields.
</p>
</body>
</html>
