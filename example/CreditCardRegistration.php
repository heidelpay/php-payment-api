<?php
namespace Heidelpay\Example\PhpPaymentApi;

/**
 * Credit card registration example
 *
 * This is a coding example for credit card registration using heidelpay php-payment-api
 * extension.
 *
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-payment-api/
 *
 * @author  Jens Richter
 *
 * @category example
 */

/**
 * For security reason all examples are disabled by default.
 */
use Heidelpay\PhpPaymentApi\PaymentMethods\CreditCardPaymentMethod;

require_once './_enableExamples.php';
if (defined('HEIDELPAY_PHP_PAYMENT_API_EXAMPLES') and HEIDELPAY_PHP_PAYMENT_API_EXAMPLES !== true) {
    exit();
}


/**
 * Require the composer autoloader file
 */
require_once __DIR__ . '/../../../autoload.php';

/**
 * Load a new instance of the payment method
 */
$CreditCard = new CreditCardPaymentMethod();

/**
 * Set up your authentification data for Heidepay api
 *
 * @link https://dev.heidelpay.com/testumgebung/#Authentifizierungsdaten
 */
$CreditCard->getRequest()->authentification(
    '31HA07BC8142C5A171745D00AD63D182',  // SecuritySender
    '31ha07bc8142c5a171744e5aef11ffd3',  // UserLogin
    '93167DE7',                          // UserPassword
    '31HA07BC8142C5A171744F3D6D155865',  // TransactionChannel credit card without 3d secure
    true                                 // Enable sandbox mode
);
/**
 * Set up asynchronous request parameters
 */
$CreditCard->getRequest()->async(
    'EN', // Language code for the Frame
    HEIDELPAY_PHP_PAYMENT_API_URL .
    HEIDELPAY_PHP_PAYMENT_API_FOLDER .
    'HeidelpayResponse.php'  // Response url from your application
);

/**
 * Set up customer information required for risk checks
 */
$CreditCard->getRequest()->customerAddress(
    'Heidel',                  // Given name
    'Berger-Payment',         // Family name
    null,                   // Company Name
    '12344',                   // Customer id of your application
    'Vagerowstr. 18',       // Billing address street
    'DE-BW',                   // Billing address state
    '69115',                   // Billing address post code
    'Heidelberg',              // Billing address city
    'DE',                      // Billing address country code
    'support@heidelpay.com'     // Customer mail address
);

/**
 * Set up basket or transaction information
 */
$CreditCard->getRequest()->basketData(
    '2843294932', // Reference Id of your application
    23.12,                         // Amount of this request
    'EUR',                         // Currency code of this request
    '39542395235ßfsokkspreipsr'    // A secret passphrase from your application
);

/**
 * Set necessary parameters for Heidelpay payment Frame and send a registration request
 */
$CreditCard->registration(
    HEIDELPAY_PHP_PAYMENT_API_URL,
    // PaymentFrameOrigin - uri of your application like https://dev.heidelpay.com
    'FALSE',
    // PreventAsyncRedirect - this will tell the payment weather it should redirect the customer or not
    HEIDELPAY_PHP_PAYMENT_API_URL .
    HEIDELPAY_PHP_PAYMENT_API_FOLDER .
    'style.css'   // CSSPath - css url to style the Heidelpay payment frame
);
?>
<html>
<head>
    <title>Credit card registration example</title>
</head>
<body>
<form method="post" class="formular" id="paymentFrameForm">
    <?php
    if ($CreditCard->getResponse()->isSuccess()) {
        echo '<iframe id="paymentIframe" src="' . $CreditCard->getResponse()->getPaymentFormUrl() . '" style="height:250px;"></iframe><br />';
    } else {
        echo '<pre>' . print_r($CreditCard->getResponse()->getError(), 1) . '</pre>';
    }
    ?>
    <button type="submit">Submit data</button>
</form>
<script type="text/javascript" src="./js/creditCardFrame.js"></script>
</body>
</html>
 
 
 
 