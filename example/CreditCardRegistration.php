<?php
namespace Heidelpay\Example\PhpApi;

/**
 * Credit card registration example
 *
 * This is a coding example for credit card registration using heidelpay php-api
 * extension.
 *
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  https://dev.heidelpay.de/PhpApi
 *
 * @author  Jens Richter
 *
 * @category example
 */

/**
 * For security reason all examples are disabled by default.
 */
require_once './_enableExamples.php';
if (defined('HeidelpayPhpApiExamples') and HeidelpayPhpApiExamples !== true) {
    exit();
}


/**
 * Require the composer autoloader file
 */
require_once __DIR__ . '/../../../autoload.php';

/**
 * Load a new instance of the payment method
 */
$CreditCard = new \Heidelpay\PhpApi\PaymentMethods\CreditCardPaymentMethod();

/**
 * Set up your authentification data for Heidepay api
 *
 * @link https://dev.heidelpay.de/testumgebung/#Authentifizierungsdaten
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
    'EN', // Languarge code for the Frame
    HeidelpayPhpApiURL . HeidelpayPhpApiFolder . 'HeidelpayResponse.php'  // Response url from your application
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
    'support@heidelpay.de'     // Customer mail address
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
    HeidelpayPhpApiURL,
    // PaymentFrameOrigin - uri of your application like https://dev.heidelpay.de
    'FALSE',
    // PreventAsyncRedirect - this will tell the payment weather it should redirect the customer or not
    HeidelpayPhpApiURL . HeidelpayPhpApiFolder . 'style.css'   // CSSPath - css url to style the Heidelpay payment frame
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
    </td>
</form>
<script type="text/javascript" src="./js/creditCardFrame.js"></script>
</body>
</html>
 
 
 
 