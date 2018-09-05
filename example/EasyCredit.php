<?php
namespace Heidelpay\Example\PhpPaymentApi;

/**
 * EasyCredit example
 *
 * This is a coding example for invoice authorize using heidelpay php-payment-api
 * extension.
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-payment-api/
 *
 * @author  Simon Gabriel
 *
 * @category example
 */

/**
 * For security reason all examples are disabled by default.
 */

use Heidelpay\PhpPaymentApi\PaymentMethods\EasyCreditPaymentMethod;

require_once './_enableExamples.php';
if (defined('HEIDELPAY_PHP_PAYMENT_API_EXAMPLES') and HEIDELPAY_PHP_PAYMENT_API_EXAMPLES !== true) {
    exit();
}


/**
 * Require the composer autoloader file
 */
require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Load a new instance of the payment method
 */
 $easyCredit = new EasyCreditPaymentMethod();

/**
 * Set up your authentification data for Heidepay api
 *
 * @link https://dev.heidelpay.com/testumgebung/#Authentifizierungsdaten
 */
$easyCredit->getRequest()->authentification(
    '31HA07BC8181E8CCFDAD64E8A4B3B766',  // SecuritySender
    '31ha07bc8181e8ccfdad73fd513d2a53',  // UserLogin
    '4B2D4BE3',                          // UserPassword
    '31HA07BC8179C95F6B59366492FD253D',  // TransactionChannel credit card without 3d secure
    true                                 // Enable sandbox mode
);
/**
 * Set up asynchronous request parameters
 */
$easyCredit->getRequest()->async(
    'EN', // Language code for the Frame
    HEIDELPAY_PHP_PAYMENT_API_URL .
    HEIDELPAY_PHP_PAYMENT_API_FOLDER .
    'EasyCreditResponse.php'  // Response url from your application
);
/**
 * Set up customer information required for risk checks
 */
$easyCredit->getRequest()->customerAddress(
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
$easyCredit->getRequest()->basketData(
    '2843294932', // Reference Id of your application
    203.12,                         // Amount of this request
    'EUR',                         // Currency code of this request
    '39542395235ßfsokkspreipsr'    // A secret passphrase from your application
);
/**
 * Set up risk information.
 */
$easyCredit->getRequest()->getRiskInformation()
    ->setCustomerGuestCheckout('false')
    ->setCustomerOrderCount('23')
    ->setCustomerSince('2005-08-12');
/**
 * Set necessary parameters for Heidelpay payment and send the request
 */
$easyCredit->initialize();

?>
<html>
<head>
	<title>EasyCredit example</title>
</head>
<body>
<?php
$response = $easyCredit->getResponse();
if ($response->isSuccess()) {
        echo '<input type="checkbox" required value="true"/><p>';
        echo $response->getConfig()->getOptinText();
        echo'</p><a href="'. $response->getPaymentFormUrl().'">Weiter zu easyCredit...</a>';
    } else {
        echo '<pre>'. print_r($response->getError(), 1).'</pre>';
    }
 ?>
 <p>It is not necessary to show the redirect url to your customer. You can  
 use php header to forward your customer directly.<br/>
 For example:<br/>
 header('Location: '.$Invoice->getResponse()->getPaymentFromUrl());
 </p> 
 </body>
 </html>
 
 
 