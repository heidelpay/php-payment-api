<?php
namespace Heidelpay\Example\PhpPaymentApi;

/**
 * iDeal authorize example
 *
 * This is a coding example for iDeal authorize using heidelpay php-api
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
use Heidelpay\PhpPaymentApi\PaymentMethods\IDealPaymentMethod;

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
 $iDeal = new IDealPaymentMethod();
 
 /**
  * Set up your authentification data for Heidepay api
  *
  * @link https://dev.heidelpay.de/testumgebung/#Authentifizierungsdaten
  */
 $iDeal->getRequest()->authentification(
       '31HA07BC8142C5A171745D00AD63D182',  // SecuritySender
       '31ha07bc8142c5a171744e5aef11ffd3',  // UserLogin
       '93167DE7',                          // UserPassword
       '31HA07BC8142C5A171744B56E61281E5',  // TransactionChannel credit card without 3d secure
       true                                 // Enable sandbox mode
     );
 /**
  * Set up asynchronous request parameters
  */
 $iDeal->getRequest()->async(
     'EN', // Language code for the Frame
     HEIDELPAY_PHP_PAYMENT_API_URL .
     HEIDELPAY_PHP_PAYMENT_API_FOLDER .
     'HeidelpayResponse.php'  // Response url from your application
 );
 
 /**
  * Set up customer information required for risk checks
  */
 $iDeal->getRequest()->customerAddress(
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
 $iDeal->getRequest()->basketData(
     '2843294932', // Reference Id of your application
     23.12,                         // Amount of this request
     'EUR',                         // Currency code of this request
     '39542395235ßfsokkspreipsr'    // A secret passphrase from your application
     );
 
 /**
  * Set necessary parameters for iDeal authorize using heidelpay php-api
  */
 $iDeal->authorize(
     );
 ?>
<html>
<head>
	<title>iDeal authorize example</title>
</head>
<body>
<form method="post" class="formular" action="
<?php 
    if ($iDeal->getResponse()->isSuccess()) {
        echo $iDeal->getResponse()->getPaymentFormUrl();
    }
?>
" id="paymentFrameForm"> 
<?php 
    if ($iDeal->getResponse()->isError()) {
        echo '<pre>'. print_r($iDeal->getResponse()->getError(), 1).'</pre>';
    }
 ?>
 Bankland:<select name="ACCOUNT.COUNTRY">
 <?php foreach ($iDeal->getResponse()->getConfig()->getBankCountry() as $cKey => $cValue) {
     echo '<option value="'.$cKey.'">'.$cValue.'</option>';
 }
 ?>
 </select><br/>
 Bankname<select name="ACCOUNT.BANKNAME">
 <?php foreach ($iDeal->getResponse()->getConfig()->getBrands() as $cKey => $cValue) {
     echo '<option value="'.$cKey.'">'.$cValue.'</option>';
 }
 ?>
 </select><br/>
 Holder:<input type="text" name="ACCOUNT.HOLDER" value="" /><br/>
 <button type="submit">Submit data</button>
 </form>
 </body>
 </html>
 
 
 
 