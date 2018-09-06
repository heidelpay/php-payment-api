<?php
namespace Heidelpay\Example\PhpPaymentApi;

/**
 * Handles response for EasyCredit example
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

const EASY_CREDIT_RESPONSE_PARAMS_TXT = __DIR__ . '/EasyCreditResponseParams.txt';
require_once './_enableExamples.php';

/**
 * Require the composer autoloader file
 */
require_once __DIR__ . '/../vendor/autoload.php';

$params = json_decode(file_get_contents(EASY_CREDIT_RESPONSE_PARAMS_TXT),1);

$response = Response::fromPost($params);

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

/**
 * Set necessary parameters for Heidelpay payment and send the request
 */
$easyCredit->authorizeOnRegistration($response->getIdentification()->getUniqueId());

$url = HEIDELPAY_PHP_PAYMENT_API_URL . HEIDELPAY_PHP_PAYMENT_API_FOLDER . '/HeidelpaySuccess.php';

if ($response->isError()) {
    $url = HEIDELPAY_PHP_PAYMENT_API_URL . HEIDELPAY_PHP_PAYMENT_API_FOLDER . '/HeidelpayError.php';
}
Header('Location: ' . $url);
?>