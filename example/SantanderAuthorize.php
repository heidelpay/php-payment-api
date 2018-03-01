<?php
namespace Heidelpay\Example\PhpPaymentApi;

/**
 * Invoice b2c secured authorize example
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
 * @author  Jens Richter
 *
 * @category example
 */

/**
 * For security reason all examples are disabled by default.
 */
use Heidelpay\PhpPaymentApi\PaymentMethods\SantanderInvoicePaymentMethod;

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
$Invoice = new SantanderInvoicePaymentMethod();

/**
 * Set up your authentification data for Heidepay api
 *
 * @link https://dev.heidelpay.com/testumgebung/#Authentifizierungsdaten
 */
$Invoice->getRequest()->authentification(
    '31HA07BC8142C5A171745D00AD63D182',  // SecuritySender
    '31ha07bc8142c5a171744e5aef11ffd3',  // UserLogin
    '93167DE7',                          // UserPassword
    '31HA07BC81856CAD6D8E07858ACD6CFB',  // TransactionChannel credit card without 3d secure
    true                                 // Enable sandbox mode
);
/**
 * Set up asynchronous request parameters
 */
$Invoice->getRequest()->async(
    'EN', // Language code for the Frame
    HEIDELPAY_PHP_PAYMENT_API_URL .
    HEIDELPAY_PHP_PAYMENT_API_FOLDER .
    'HeidelpayResponse.php'  // Response url from your application
);

/**
 * Set up customer information required for risk checks
 */
$Invoice->getRequest()->customerAddress(
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

/**
 * Set up basket or transaction information
 */
$Invoice->getRequest()->basketData(
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
$Invoice->getRequest()->b2cSecured(
    'MR',  // Customer salutation
    '1982-07-12', // Customer birth date
    null // Basket api id
);


/**
 * Set necessary parameters for Heidelpay payment and send the request
 */
$Invoice->authorize();

$optinText = $Invoice->getResponse()->getConfig()->getOptinText();
?>
<html>
<head>
    <title>Santander Excample example</title>
</head>
<body>
<form method="post" class="formular" action="
<?php
if ($Invoice->getResponse()->isSuccess()) {
    echo $Invoice->getResponse()->getPaymentFormUrl();
}
?>
" id="paymentFrameForm">
    <?php
    if ($Invoice->getResponse()->isError()) {
        echo '<pre>'. print_r($Invoice->getResponse()->getError(), 1).'</pre>';
    }
    ?>
    <p>
        <input type="checkbox" name="CUSTOMER.OPTIN" value="TRUE" checked="checked">
        <?php echo $optinText['optin'] ?>
    </p>
    <p>
        <?php echo $optinText['privacy_policy'] ?>
    </p>
    Birth date: <input type="text" name="NAME.BIRTHDATE" value="YYYY-MM-DD" /><br/>
    Salutation: <select name="NAME.SALUTATION" >
        <option value="MRS">Frau</option>
        <option value="MR">Herr</option>
    </select>
        <br/>
        <br/>
    <button type="submit">Kauf best&auml;tigen</button>
</form>
</body>
</html>
 
 