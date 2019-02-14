<?php
namespace Heidelpay\Example\PhpPaymentApi;

/**
 * The reversal is done by the Merchant and not the Customer. Therefore this step belongs to the backend of the Shop,
 * only accessible by the Merchant.
 *
 * Performs the reversal transaction for the last Reservation saved in FactoringInvoiceResponseParams.txt
 * This is a coding example for reversal using heidelpay php-payment-api extension.
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-payment-api/
 *
 * @author  David Owusu
 *
 * @category example
 */

use Heidelpay\PhpPaymentApi\Constants\ReversalType;
use Heidelpay\PhpPaymentApi\PaymentMethods\InvoiceB2CSecuredPaymentMethod;
use Heidelpay\PhpPaymentApi\Response;

//#######   Checks whether examples are enabled. #######################################################################
require_once __DIR__ . '/FactoringInvoiceConstants.php';

// Require the composer autoloader file
$path = __DIR__;
$splitpath = str_replace('heidelpay/php-payment-api/example/FactoringInvoice',"",$path);
require_once $splitpath . '/autoload.php';

//####### 10. Since we again need the information of the reservation transaction #########
$params = json_decode(file_get_contents(RESPONSE_FILE_NAME), 1);
$response = Response::fromPost($params);

// Display button to trigger the reversal request.
if (empty($_POST) && !empty($response)) {
    echo'Should the Transaction: ShortID: ' . $response->getIdentification()->getShortId() . ' and the amount of '
        . $response->getPresentation()->getAmount() .' ' . $response->getPresentation()->getCurrency()
        . ' be reversed?';
    echo '<form action="' . REVERSAL_URL . '" method="POST">';
    echo '<input type="submit" name="reversal_last_transaction" value="Reversal"/>';
    echo '</form>';
    return;
}

// Check whether reversal was confirmed my merchant.
if(empty($_POST['reversal_last_transaction'])) {
    echo print_r($_POST, 1);
    return;
}

//####### 11.1 We now prepare a reversal request however this has a few differences:                     ###############
/**
 * Load a new instance of the payment method
 */
$factoringInvoice = new InvoiceB2CSecuredPaymentMethod();

/**
 * Set up your authentification data for heidepay api
 *
 * @link https://dev.heidelpay.com/testumgebung/#Authentifizierungsdaten
 */
$factoringInvoice->getRequest()->authentification(
    HEIDELPAY_SECURITY_SENDER,  // SecuritySender
    HEIDELPAY_USER_LOGIN,  // UserLogin
    HEIDELPAY_USER_PASSWORD,  // UserPassword
    HEIDELPAY_TRANSACTION_CHANNEL,  // TransactionChannel credit card without 3d secure
    true                                 // Enable sandbox mode
);

 /**
  * ####### 11.1. This time we do a sync request rather than an async request as before. We do this for the sake of the
  * ####### readability of the example. This results in the payment server sending the response to the request
  * ####### immediately in the http-response of this request rather then sending it asynchronously to the responseUrl
  * ####### (as seen before).
  *
  * Set up synchronous request parameters
  */
$factoringInvoice->getRequest()->getFrontend()->setEnabled('FALSE');

// Set up basket for transaction information. The amount that is set here will be the amount of the reversal.
$factoringInvoice->getRequest()->basketData(
    '2843294932',               // Reference Id of your application
    $response->getPresentation()->getAmount(), // Amount of reversal:
    $response->getPresentation()->getCurrency(), // Currency code of this request
    '39542395235ßfsokkspreipsr'     // A secret passphrase from your application
);

/* ###### 11.2. This time we call the method reversal passing along the uniqueId of the previous #######
 * ######       reservation as a reference to let the payment server know which payment we want to reversal.
 * ######       The difference to a normal reversal is the additional Parameter $reversalType
 * ######       The api provides a Class to provide the available reversal types as constants.
 *
 *
 */

// Set necessary parameters for heidelpay payment and send the request.
// For an other reversalType please enable code below. (only one reversal at a time)
$factoringInvoice->reversal($response->getPaymentReferenceId(), ReversalType::RT_CANCEL);
//$factoringInvoice->reversal($response->getPaymentReferenceId(), ReversalType::RT_CREDIT);
//$factoringInvoice->reversal($response->getPaymentReferenceId(), ReversalType::RT_RETURN);

$reversalResponse = $factoringInvoice->getResponse();

//####### 12. Now we redirect to the success or error page depending on the result of the request. #####################
//#######     Keep in mind there are three possible results: Success, Pending and Error.                               #
//#######     Since both pending and success indicate a successful handling by the payment server both should          #
//#######     redirect to the success page.                                                                            #
$url = HEIDELPAY_SUCCESS_PAGE;
if ($reversalResponse->isError()) {
    $url = HEIDELPAY_FAILURE_PAGE . '?errorMessage=' . $reversalResponse->getError()['message'];
}

// Clear transaction. This is only necessary for this for this example since we only safe the one transaction at a time.
if ($reversalResponse->isSuccess()) {
    file_put_contents (RESPONSE_FILE_NAME, null);
}

header('Location: ' . $url); // perform the redirect
