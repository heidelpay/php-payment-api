<?php
namespace Heidelpay\Example\PhpPaymentApi;

/**
 * Przelewy24 authorize example
 *
 * This is a coding example for Sofort authorize using the heidelpay php-api
 * extension.
 *
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author  Ronja Wann
 *
 * @category example
 */

/**
 * For security reason all examples are disabled by default.
 */
use Heidelpay\PhpPaymentApi\PaymentMethods\Przelewy24PaymentMethod;

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
$Przelewy24 = new Przelewy24PaymentMethod();

$Przelewy24->getRequest()->authentification(
       '31HA07BC8142C5A171745D00AD63D182',  // SecuritySender
       '31ha07bc8142c5a171744e5aef11ffd3',  // UserLogin
       '93167DE7',                          // UserPassword
       '31HA07BC811BAF9BED1129D1160BF318',  // TransactionChannel Przelewy24
       true                                 // Enable sandbox mode
     );

$Przelewy24->getRequest()->customerAddress(

        'Heidel',                  // Given name
        'Berger-Payment',           // Family name
        null,                     // Company Name
        '12344',                   // Customer id of your application
        'Vagerowstr. 18',          // Billing address street
        'DE-BW',                   // Billing address state
        '69115',                   // Billing address post code
        'Heidelberg',              // Billing address city
        'PL',                      // Billing address country code
        'support@heidelpay.de'     // Customer mail address
        
        );

$Przelewy24->getRequest()->async(
    'EN', // Language code for the Frame
    HEIDELPAY_PHP_PAYMENT_API_URL .
    HEIDELPAY_PHP_PAYMENT_API_FOLDER .
    'HeidelpayResponse.php'  // Response url from your application
);

$Przelewy24->getRequest()->basketData(
        
        '2843294932',                  // Reference Id of your application
        23.12,                         // Amount of this request
        'PLN',                         // Currency code of this request
        '39542395235ßfsokkspreipsr'    // A secret passphrase from your application
        
        );

$Przelewy24->authorize();
?>
<html>
<head>
	<title>Przelewy24 authorize example</title>
</head>
<body>
<?php 
    if ($Przelewy24->getResponse()->isSuccess()) {
        echo '<a href="'.$Przelewy24->getResponse()->getPaymentFormUrl().'">to Przelewy24</a>';
    } else {
        echo '<pre>'. print_r($Przelewy24->getResponse()->getError(), 1).'</pre>';
    }
 ?>
 <p>It is not necessary to show the redirect url to your customer. You can  
 use php header to forward your customer directly.<br/>
 For example:<br/>
 header('Location: '.$Przelewy24->getResponse()->getPaymentFromUrl());
 </p> 
 </body>
 </html>
 
 
 
 