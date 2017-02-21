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
 * @link  https://dev.heidelpay.de/PhpApi
 *
 * @author  Jens Richter
 *
 * @category example
 */

/*
 * For security reason all examples are disabled by default.
 */
require_once './_enableExamples.php';
if (defined('HeidelpayPhpApiExamples') and HeidelpayPhpApiExamples !== true) {
    exit();
}

/*Require the composer autoloader file */
require_once __DIR__ . '/../../../autoload.php';

$HeidelpayResponse = new  Heidelpay\PhpApi\Response($_POST);

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
    echo HeidelpayPhpApiURL.HeidelpayPhpApiFolder.'HeidelpaySuccess.php';
    
    /*save order */
} elseif ($HeidelpayResponse->isError()) {
    $error = $HeidelpayResponse->getError();
    
    echo HeidelpayPhpApiURL.HeidelpayPhpApiFolder.'HeidelpayError.php?errorMessage='.urlencode(htmlspecialchars($error['message']));
}
