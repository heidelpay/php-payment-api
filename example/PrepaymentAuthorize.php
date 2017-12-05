<?php
namespace Heidelpay\Example\PhpPaymentApi;

/**
 * Prepayment authorize example
 *
 * This is a coding example for prepayment authorize using heidelpay php-api
 * extension.
 *
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author  Jens Richter
 *
 * @category example
 */

/**
 * For security reason all examples are disabled by default.
 */
use Heidelpay\PhpPaymentApi\PaymentMethods\PrepaymentPaymentMethod;

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
 $Prepayment = new PrepaymentPaymentMethod();
 
 /**
  * Set up your authentification data for Heidepay api
  *
  * @link https://dev.heidelpay.de/testumgebung/#Authentifizierungsdaten
  */
 $Prepayment->getRequest()->authentification(
       '31HA07BC8142C5A171745D00AD63D182',  // SecuritySender
       '31ha07bc8142c5a171744e5aef11ffd3',  // UserLogin
       '93167DE7',                          // UserPassword
       '31HA07BC8142C5A171744F3D6D155865',  // TransactionChannel credit card without 3d secure
       true                                 // Enable sandbox mode
     );
 /**
  * Set up asynchronous request parameters
  */
 $Prepayment->getRequest()->async(
     'EN', // Language code for the Frame
     HEIDELPAY_PHP_PAYMENT_API_URL .
     HEIDELPAY_PHP_PAYMENT_API_FOLDER .
     'HeidelpayResponse.php'  // Response url from your application
 );
 
 /**
  * Set up customer information required for risk checks
  */
 $Prepayment->getRequest()->customerAddress(
     'Heidel',                  // Given name
     'Berger-Payment',           // Family name
     null,                     // Company Name
     '12344',                   // Customer id of your application
     'Vagerowstr. 18',          // Billing address street
     'DE-BW',                   // Billing address state
     '69115',                   // Billing address post code
     'Heidelberg',              // Billing address city
     'DE',                      // Billing address country code
     'support@heidelpay.de'     // Customer mail address
     );
 
 /**
  * Set up basket or transaction information
  */
 $Prepayment->getRequest()->basketData(
     '2843294932', // Reference Id of your application
     23.12,                         // Amount of this request
     'EUR',                         // Currency code of this request
     '39542395235ßfsokkspreipsr'    // A secret passphrase from your application
     );
 
 /**
  * Set necessary parameters for Heidelpay payment and send the request
  */
 $Prepayment->authorize();
 ?>
<html>
<head>
	<title>Prepayment authorize example</title>
</head>
<body>
<?php 
    if ($Prepayment->getResponse()->isSuccess()) {
        echo '<a href="'.$Prepayment->getResponse()->getPaymentFormUrl().'">place prepayment</a>';
    } else {
        echo '<pre>'. print_r($Prepayment->getResponse()->getError(), 1).'</pre>';
    }
 ?>
 <p>It is not necessary to show the redirect url to your customer. You can  
 use php header to forward your customer directly.<br/>
 For example:<br/>
 header('Location: '.$Prepayment->getResponse()->getPaymentFromUrl());
 </p> 
 </body>
 </html>
 
 
 