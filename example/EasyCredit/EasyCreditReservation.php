<?php
namespace Heidelpay\Example\PhpPaymentApi;

/**
 * Performs the reservation transaction for the EasyCredit example
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

use Heidelpay\PhpPaymentApi\PaymentMethods\EasyCreditPaymentMethod;
use Heidelpay\PhpPaymentApi\Response;

//#######   Checks whether examples are enabled. #######################################################################
require_once __DIR__ . '/EasyCreditConstants.php';

/**
 * Require the composer autoloader file
 */
require_once __DIR__ . '/../../vendor/autoload.php';

//####### 10. Since we again need the information on the payment plan we again load it from the response file. #########
$params = json_decode(file_get_contents(RESPONSE_FILE_NAME), 1);
$response = Response::fromPost($params);

//####### 11. We now prepare a similare request as in the beginning however this has a few differences: ################
/**
 * Load a new instance of the payment method
 */
$easyCredit = new EasyCreditPaymentMethod();
$request = $easyCredit->getRequest();

/**
 * Set up your authentification data for Heidepay api
 *
 * @link https://dev.heidelpay.com/testumgebung/#Authentifizierungsdaten
 */
$request->authentification(
    '31HA07BC8181E8CCFDAD64E8A4B3B766',  // SecuritySender
    '31ha07bc8181e8ccfdad73fd513d2a53',  // UserLogin
    '4B2D4BE3',                          // UserPassword
    '31HA07BC8179C95F6B59366492FD253D',  // TransactionChannel credit card without 3d secure
    true                                 // Enable sandbox mode
);

//####### 11.1. This time we do a sync request rather than an async request as before. We do this for the sake of the ##
//####### readability of the example. This results in the payment server sending the response to the request           #
//####### immediately in the http-response of this request rather then sending it asyncronuously to the responseUrl    #
//####### (as seen before).
/**
 * Set up asynchronous request parameters
 */
$request->getFrontend()->setEnabled('FALSE');

/**
 * Set up customer information required for risk checks
 */
$request->customerAddress(
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
$request->basketData(
    '2843294932', // Reference Id of your application
    203.12,                         // Amount of this request
    'EUR',                         // Currency code of this request
    '39542395235ßfsokkspreipsr'    // A secret passphrase from your application
);

/**
 * Set up risk information.
 */
$request->getRiskInformation()
    ->setCustomerGuestCheckout('false')
    ->setCustomerOrderCount('23')
    ->setCustomerSince('2005-08-12');

//####### 11.2. This time we call the method authorizeOnRegistration passing along the uniqueId of the previous #######
//#######       initialization as a reference to let the payment server know which payment plan to use.                #
/**
 * Set necessary parameters for heidelpay payment and send the request
 */
$easyCredit->authorizeOnRegistration($response->getIdentification()->getUniqueId());
$authorizationResponse = $easyCredit->getResponse();

//####### 12. Now we redirect to the success or error page depending on the result of the request. #####################
//#######     Keep in mind there are three possible results: Success, Pending and Error.                               #
//#######     Since both pending and success indicate a successful handling by the payment server both should         #
//#######     redirect to the success page.                                                                            #
$url = HEIDELPAY_SUCCESS_PAGE;
if ($authorizationResponse->isError()) {
    $url = HEIDELPAY_FAILURE_PAGE . '?errorMessage=' . $authorizationResponse->getError()['message'];
}

header('Location: ' . $url); // perform the redirect
?>
