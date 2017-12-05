<?php
/**
 * heidelpay response action
 *
 * This is a coding example for the response action
 *
 *
 * @license Use of this software requires acceptance of the License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author  Jens Richter
 *
 * @category example
 */

/*
 * For security reason all examples are disabled by default.
 */
require_once './_enableExamples.php';
if (defined('HEIDELPAY_PHP_PAYMENT_API_EXAMPLES') and HEIDELPAY_PHP_PAYMENT_API_EXAMPLES !== true) {
    exit();
}

/*Require the composer autoloader file */
require_once __DIR__ . '/../../../autoload.php';

$HeidelpayResponse = new  Heidelpay\PhpPaymentApi\Response($_POST);

$secretPass = "39542395235ßfsokkspreipsr";

$identificationTransactionId = $HeidelpayResponse->getIdentification()->getTransactionId();

try {
    $HeidelpayResponse->verifySecurityHash($secretPass, $identificationTransactionId);
} catch (\Exception $e) {
    /* If the verification does not match this can mean some kind of manipulation or
     * miss configuration. So you can log $e->getMessage() for debugging.*/
    return;
}
 
if ($HeidelpayResponse->isSuccess()) {
    
    /* save order and transaction result to your database */
    if ($HeidelpayResponse->isPending()) {
        /* use this to set the order status to pending */
    }
    /* redirect customer to success page */
    echo HEIDELPAY_PHP_PAYMENT_API_URL . HEIDELPAY_PHP_PAYMENT_API_FOLDER . 'HeidelpaySuccess.php';
    
    /*save order */
} elseif ($HeidelpayResponse->isError()) {
    $error = $HeidelpayResponse->getError();
    
    echo HEIDELPAY_PHP_PAYMENT_API_URL . HEIDELPAY_PHP_PAYMENT_API_FOLDER . 'HeidelpayError.php?errorMessage=' .
        urlencode(htmlspecialchars($error['message']));
}
