<?php
namespace Heidelpay\Example\PhpApi;

/**
 * Sofort authorize example
 *
 * This is a coding example for Sofort authorize using the heidelpay php-api
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
 $Sofort = new \Heidelpay\PhpApi\PaymentMethods\SofortPaymentMethod();
 
 /**
  * Set up your authentification data for heidepay api
  *
  * @link https://dev.heidelpay.de/testumgebung/#Authentifizierungsdaten
  */
 $Sofort->getRequest()->authentification(
       '31HA07BC8142C5A171745D00AD63D182',  // SecuritySender
       '31ha07bc8142c5a171744e5aef11ffd3',  // UserLogin
       '93167DE7',                          // UserPassword
       '31HA07BC8142C5A171749CDAA43365D2',  // TransactionChannel credit card without 3d secure
       true                                 // Enable sandbox mode
     );
 /**
  * Set up asynchronous request parameters
  */
 $Sofort->getRequest()->async(
        'EN', // Languarge code for the Frame
        HeidelpayPhpApiURL.HeidelpayPhpApiFolder.'HeidelpayResponse.php'  // Response url from your application
     );
 
 /**
  * Set up customer information required for risk checks
  */
 $Sofort->getRequest()->customerAddress(
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
 $Sofort->getRequest()->basketData(
     '2843294932', // Reference Id of your application
     23.12,                         // Amount of this request
     'EUR',                         // Currency code of this request
     '39542395235ßfsokkspreipsr'    // A secret passphrase from your application
     );
 
 /**
  * Send authorize request
  */
 $Sofort->authorize();
 ?>
<html>
<head>
	<title>Sofort authorize example</title>
</head>
<body>
<?php 
    if ($Sofort->getResponse()->isSuccess()) {
        echo '<a href="'.$Sofort->getResponse()->getPaymentFormUrl().'">to Sofort</a>';
    } else {
        echo '<pre>'. print_r($Sofort->getResponse()->getError(), 1).'</pre>';
    }
 ?>
 <p>It is not necessary to show the redirect url to your customer. You can  
 use php header to forward your customer directly.<br/>
 For example:<br/>
 header('Location: '.$Sofort->getResponse()->getPaymentFromUrl());
 </p> 
 </body>
 </html>
 
 
 
 