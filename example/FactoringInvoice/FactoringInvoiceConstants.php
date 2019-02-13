<?php
/**
 * Defines the constants needed through out this example.
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/
 *
 * @author  David Owusu <development@heidelpay.de>
 *
 * @package  heidelpay/${Package}
 */

require_once __DIR__ . '/../_enableExamples.php';
if (defined('HEIDELPAY_PHP_PAYMENT_API_EXAMPLES') && HEIDELPAY_PHP_PAYMENT_API_EXAMPLES !== true) {
    exit();
}

const EXAMPLE_BASE_FOLDER = HEIDELPAY_PHP_PAYMENT_API_URL . HEIDELPAY_PHP_PAYMENT_API_FOLDER;
define('RESPONSE_URL', EXAMPLE_BASE_FOLDER . 'FactoringInvoice/FactoringInvoiceResponse.php');
define('REVERSAL_URL', EXAMPLE_BASE_FOLDER . 'FactoringInvoice/FactoringInvoiceReversal.php');
define('RESPONSE_FILE_NAME', __DIR__ . '/FactoringInvoiceResponseParams.txt');
define('HEIDELPAY_SUCCESS_PAGE', EXAMPLE_BASE_FOLDER . 'HeidelpaySuccess.php');
define('HEIDELPAY_FAILURE_PAGE', EXAMPLE_BASE_FOLDER . 'HeidelpayError.php');

define('HEIDELPAY_TRANSACTION_CHANNEL', '31HA07BC8129FBA7AF65A35EC4E540C2');

require_once __DIR__ . '/../_HeidelpayConstants.php';