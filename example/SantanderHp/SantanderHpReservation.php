<?php
namespace Heidelpay\Example\PhpPaymentApi;

/**
 * Performs the reservation transaction for the Santander Hire Purchase example
 *
 * This is a coding example for hire purchace reservation using heidelpay php-payment-api
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

use Heidelpay\PhpPaymentApi\PaymentMethods\SantanderHirePurchasePaymentMethod;
use Heidelpay\PhpPaymentApi\Response;

//#######   Checks whether examples are enabled. #######################################################################
require_once __DIR__ . '/SantanderHpConstants.php';

/**
 * Require the composer autoloader file
 */
$path = __DIR__;
$splitpath = str_replace('heidelpay/php-payment-api/example/SantanderHp',"",$path);
require_once $splitpath . '/autoload.php';

//####### 10. Since we again need the information on the payment plan we again load it from the response file. #########
$params = json_decode(file_get_contents(RESPONSE_FILE_NAME), 1);
$response = Response::fromPost($params);
//####### 11. We now prepare a similare request as in the beginning however this has a few differences: ################
/**
 * Load a new instance of the payment method
 */
$santanderHp = new SantanderHirePurchasePaymentMethod();
$request = $santanderHp->getRequest();

/**
 * Set up your authentification data for Heidepay api
 *
 * @link https://dev.heidelpay.com/testumgebung/#Authentifizierungsdaten
 */
$santanderHp->getRequest()->authentification(
    '31HA07BC8142C5A171745D00AD63D182',  // SecuritySender
    '31ha07bc8142c5a171744e5aef11ffd3',  // UserLogin
    '93167DE7',                          // UserPassword
    '31HA07BC81302E8725994B52D85F062E',  // TransactionChannel credit card without 3d secure
    true                                 // Enable sandbox mode
);

//####### 11.1. This time we do a sync request rather than an async request as before. We do this for the sake of the ##
//####### readability of the example. This results in the payment server sending the response to the request           #
//####### immediately in the http-response of this request rather then sending it asyncronuously to the responseUrl    #
//####### (as seen before).
/**
 * Set up asynchronous request parameters
 */
$santanderHp->getRequest()->getFrontend()->setEnabled('FALSE');

/**
 * Set up customer information required for risk checks
 */
$santanderHp->getRequest()->customerAddress(
    'Ampel',            // Given name
    'Grün',             // Family name
    null,             // Company Name
    '12345',             // Customer id of your application
    'Lichtweg 2',       // Billing address street
    null,               // Billing address state
    '12345',             // Billing address post code
    'Laterne',           // Billing address city
    'DE',             // Billing address country code
    'g.ampel@test.de'   // Customer mail address
);

/**
 * Set up basket or transaction information. Please ensure that you do not have divergent amounts between initialize and
 * reservation transaction
 */
$santanderHp->getRequest()->basketData(
    '2843294932',               // Reference Id of your application
    203.12,                         // Amount of this request
    'EUR',                         // Currency code of this request
    '39542395235ßfsokkspreipsr'     // A secret passphrase from your application
);

/**
 * Set up risk information.
 */
$santanderHp->getRequest()->getRiskInformation()
    ->setCustomerGuestCheckout('false')
    ->setCustomerOrderCount('23')
    ->setCustomerSince('2005-08-12');

//####### 11.2. This time we call the method authorizeOnRegistration passing along the uniqueId of the previous #######
//#######       initialization as a reference to let the payment server know which payment plan to use.                #
/**
 * Set necessary parameters for heidelpay payment and send the request
 */
$santanderHp->authorizeOnRegistration($response->getIdentification()->getUniqueId());
$authorizationResponse = $santanderHp->getResponse();

//####### 12. Now we redirect to the success or error page depending on the result of the request. #####################
//#######     Keep in mind there are three possible results: Success, Pending and Error.                               #
//#######     Since both pending and success indicate a successful handling by the payment server both should          #
//#######     redirect to the success page.                                                                            #
$url = HEIDELPAY_SUCCESS_PAGE;
if ($authorizationResponse->isError()) {
    $url = HEIDELPAY_FAILURE_PAGE . '?errorMessage=' . $authorizationResponse->getError()['message'];
}

header('Location: ' . $url); // perform the redirect


//####### 13. After placing that order / reservation transaction you have to report the shiping.  ######################
//####### to faciliate this example, this is just an example of code because you can also trigger this transaction via #
//####### heidelpay-Hip                                                                                                #

//$santanderHp->finalize($paymentReferenceIdOfReservationTransaction);
?>

