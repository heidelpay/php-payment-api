<?php
namespace Heidelpay\Example\PhpPaymentApi;

/**
 * Santander Hire Purchase example
 *
 * This is a coding example for hire purchase of Sanatnder using heidelpay php-payment-api
 * extension.
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-payment-api/
 *
 * @author  Sascha Pflüger
 *
 * @category example
 */

/**
 * For security reason all examples are disabled by default.
 */

use Heidelpay\PhpPaymentApi\PaymentMethods\SantanderHirePurchasePaymentMethod;

//#######   Checks whether examples are enabled. #######################################################################
require_once __DIR__ . '/SantanderHp/SantanderHpConstants.php';

/**
 * Require the composer autoloader file
 */
require_once __DIR__ . '/../../../autoload.php';

//#######   Checks whether the response file is writable. ##############################################################
//#######   The results from the Santander initialize Response will be sent via POST in a server-to-server request.    #
//#######   Normally the information will be stored within the database to make them accessable in the customer session#
//#######   in order to safe the information in this example we use the file defined in RESPONSE_FILE_NAME constant.   #
//#######   Only necessary for this example.                                                                           #
if (!is_writable(RESPONSE_FILE_NAME)) {
    echo '<h1>Santander Hire Purchase example</h1>';
    echo 'File: ' . RESPONSE_FILE_NAME . ' is not writable or does not exist. Please change permissions.';
    exit;
}

//####### 1. Create an instance of the SantandeHirePurchase payment method. ######################################################
/**
 * Load a new instance of the payment method
 */
$santanderHp = new SantanderHirePurchasePaymentMethod();

//####### 2. Prepare and send an initialization request. ######################################################
//#######    The response will provide a redirectUrl to a site where the customer can select the payment plan #
//#######    Please make sure to set the riskinformation (see below) in order to increase acceptance rate.    #

/**
 * Set up your authentification data for Heidepay api
 *
 * @link https://dev.heidelpay.com/testumgebung/#Authentifizierungsdaten
 */
$santanderHp->getRequest()->authentification(
    '31HA07BC8142C5A171745D00AD63D182',  // SecuritySender
    '31ha07bc8142c5a171744e5aef11ffd3',     // UserLogin
    '93167DE7',                          // UserPassword
    '31HA07BC81302E8725994B52D85F062E',  // TransactionChannel credit card without 3d secure
    true                                 // Enable sandbox mode
);

/**
 * Set up asynchronous request parameters
 */
$santanderHp->getRequest()->async(
    'DE', // Language code for the Frame
    RESPONSE_URL  // Response url from your application
);

/**
 * Set up customer information required for risk checks. For a declined transaction please enable code below.
 */
$santanderHp->getRequest()->customerAddress(
    'Grün',            // Given name
    'Ampel',             // Family name
    null,             // Company Name
    '12345',             // Customer id of your application
    'Lichtweg 2',       // Billing address street
    null,               // Billing address state
    '12345',             // Billing address post code
    'Laterne',           // Billing address city
    'DE',             // Billing address country code
    'g.ampel@test.de'   // Customer mail address
);
//$santanderHp->getRequest()->customerAddress(
//    'Rot',            // Given name
//    'Ampel',             // Family name
//    null,             // Company Name
//    '12345',             // Customer id of your application
//    'Lichtweg 2',       // Billing address street
//    null,               // Billing address state
//    '12345',             // Billing address post code
//    'Laterne',           // Billing address city
//    'DE',             // Billing address country code
//    'r.ampel@test.de'   // Customer mail address
//);

/**
 * Set up basket or transaction information
 */
$santanderHp->getRequest()->basketData(
    '2843294932',               // Reference Id of your application
    203.12,                     // Amount of this request
    'EUR',                      // Currency code of this request
    '39542395235ßfsokkspreipsr' // A secret passphrase from your application
);

/**
 * Set up risk information.
 */
$santanderHp->getRequest()->getRiskInformation()
    ->setCustomerGuestCheckout('false')
    ->setCustomerOrderCount('23')
    ->setCustomerSince('2005-08-12');

/**
 * Set up Customer-specific data for Santander hire purchase (salutation and bithdate)
 */
$santanderHp->getRequest()->getName()->setBirthdate("1987-12-12");
$santanderHp->getRequest()->getName()->setSalutation("MR");

/**
 * Set necessary parameters for heidelpay payment and send the request
 */
$santanderHp->initialize();

?>
<html>
<head>
    <title>Santander Hire Purchase example</title>
</head>
<body>
<?php
//####### 3. Show the customer the link to hire purchase rate plan and Santander Logo or redirect directly ############################
//#######    When the customer selected a payment plan the responseUrl which is send with the above request will be    #
//#######    called by the heidelpay payment server.
//#######    For next steps see the file defined with the RESPONSE_URL constant.
echo '<h1>Santander Hire Purchase example</h1>';
$responseHpIn = $santanderHp->getResponse();

if ($responseHpIn->isSuccess()) {
    // Logo has to be Shown to the customer
    echo '<img src="https://www.santander.de/media/bilder/logos/logos_privatkunden/logo.gif" alt="Santander-Logo"><br>';
    echo '<button type="button"><a href="'.$responseHpIn->getPaymentFormUrl().'">Zum Santander Ratenplan</a></button>';
} else {
    echo '<pre>'. print_r($responseHpIn->getError(), 1).'</pre>';
}
?>

</body>
</html>
