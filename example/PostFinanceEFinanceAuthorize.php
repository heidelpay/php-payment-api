<?php
namespace Heidelpay\Example\PhpApi;

/**
 * PostFinanceEFinance authorize example
 *
 * This is a coding example for PostFinance EFinance authorize using the heidelpay php-api
 * extension.
 *
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  https://dev.heidelpay.de/PhpApi
 *
 * @author  Ronja Wann
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
$PostFinanceEFinance = new \Heidelpay\PhpApi\PaymentMethods\PostFinanceEFinancePaymentMethod();

$PostFinanceEFinance->getRequest()->authentification(
       '31HA07BC8142C5A171745D00AD63D182',  // SecuritySender
       '31ha07bc8142c5a171744e5aef11ffd3',  // UserLogin
       '93167DE7',                          // UserPassword
       '31HA07BC817E5CF74624746925703A51',  // TransactionChannel PostFinance EFinance
       true                                 // Enable sandbox mode
     );

$PostFinanceEFinance->getRequest()->customerAddress(

        'Heidel',                  // Given name
        'Berger-Payment',           // Family name
        null,                     // Company Name
        '12344',                   // Customer id of your application
        'Vagerowstr. 18',          // Billing address street
        'DE-BW',                   // Billing address state
        '69115',                   // Billing address post code
        'Heidelberg',              // Billing address city
        'CH',                      // Billing address country code
        'support@heidelpay.de'     // Customer mail address
        
        );

$PostFinanceEFinance->getRequest()->async(
        
        'EN', // Languarge code for the Frame
        HeidelpayPhpApiURL.HeidelpayPhpApiFolder.'HeidelpayResponse.php'  // Response url from your application
                
   );

$PostFinanceEFinance->getRequest()->basketData(
        
        '2843294932',                  // Reference Id of your application
        23.12,                         // Amount of this request
        'CHF',                         // Currency code of this request
        '39542395235ßfsokkspreipsr'    // A secret passphrase from your application
        
        );

$PostFinanceEFinance->authorize();
?>
<html>
<head>
	<title>PostFinance Card authorize example</title>
</head>
<body>
<?php 
    if ($PostFinanceEFinance->getResponse()->isSuccess()) {
        echo '<a href="'.$PostFinanceEFinance->getResponse()->getPaymentFormUrl().'">to PostFinance Card</a>';
    } else {
        echo '<pre>'. print_r($PostFinanceEFinance->getResponse()->getError(), 1).'</pre>';
    }
 ?>
 <p>It is not necessary to show the redirect url to your customer. You can  
 use php header to forward your customer directly.<br/>
 For example:<br/>
 header('Location: '.$PostFinanceEFinance->getResponse()->getPaymentFromUrl());
 </p> 
 </body>
 </html>
 
 
 
 