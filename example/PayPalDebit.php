<?php
namespace Heidelpay\Example\PhpPaymentApi;

/**
 * PayPal debit example
 *
 * This is a coding example for PayPal debit using heidelpay php-payment-api
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
use Heidelpay\PhpPaymentApi\PaymentMethods\PayPalPaymentMethod;

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
 $PayPal = new PayPalPaymentMethod();
 
 /**
  * Set up your authentification data for heidepay api
  *
  * @link http://dev.heidelpay.com/testumgebung/#Authentifizierungsdaten
  */
 $PayPal->getRequest()->authentification(
       '31HA07BC8142C5A171745D00AD63D182',  // SecuritySender
       '31ha07bc8142c5a171744e5aef11ffd3',  // UserLogin
       '93167DE7',                          // UserPassword
       '31HA07BC8142C5A171749A60D979B6E4',  // TransactionChannel credit card without 3d secure
       true                                 // Enable sandbox mode
     );
 /**
  * Set up asynchronous request parameters
  */
 $PayPal->getRequest()->async(
     'EN', // Language code for the Frame
     HEIDELPAY_PHP_PAYMENT_API_URL .
     HEIDELPAY_PHP_PAYMENT_API_FOLDER .
     'HeidelpayResponse.php'  // Response url from your application
 );
 
 /**
  * Set up customer information required for risk checks
  */
 $PayPal->getRequest()->customerAddress(
     'Heidel',                  // Given name
     'Berger-Payment',           // Family name
     null,                      // Company Name
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
 $PayPal->getRequest()->basketData(
     '2843294932', // Reference Id of your application
     23.12,                         // Amount of this request
     'EUR',                         // Currency code of this request
     '39542395235ßfsokkspreipsr'    // A secret passphrase from your application
     );
 
 /**
  * Set necessary parameters for Heidelpay payment Frame and send a registration request
  */
 $PayPal->debit();
 ?>
<html>
<head>
	<title>PayPal debit example</title>
</head>
<body>
<?php 
    if ($PayPal->getResponse()->isSuccess()) {
        echo '<a href="'.$PayPal->getResponse()->getPaymentFormUrl().'">to PayPal</a>';
    } else {
        echo '<pre>'. print_r($PayPal->getResponse()->getError(), 1).'</pre>';
    }
 ?>
 <p>It is not necessary to show the redirect url to your customer. You can  
 use php header to forward your customer directly.<br/>
 For example:<br/>
 header('Location: '.$PayPal->getResponse()->getPaymentFromUrl());
 </p> 
 </body>
 </html>
 
 
 
 