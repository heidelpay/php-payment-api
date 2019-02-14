<?php
namespace Heidelpay\Example\PhpPaymentApi;

/**
 * Handles response for invoice reservation with factoring example
 *
 * This is a coding example for invoice authorize using heidelpay php-payment-api
 * extension.
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

use Heidelpay\PhpPaymentApi\PaymentMethods\InvoiceB2CSecuredPaymentMethod;
use Heidelpay\PhpPaymentApi\Response;

//#######   Checks whether examples are enabled. #######################################################################
require_once __DIR__ . '/FactoringInvoiceConstants.php';

/**
 * Require the composer autoloader file
 */
$path = __DIR__;
$splitpath = str_replace('heidelpay/php-payment-api/example/FactoringInvoice',"",$path);
require_once $splitpath . '/autoload.php';

//####### 4. This page is called in a server-to-server request as soon as the customer selected a payment plan.        #

//####### 5. In this request we will do following:                                                                     #
//#######       We will store the post data withing the reponse file (see RESPONSE_FILE_NAME constant) to make it      #
//#######       available in the customer session.


if (!empty($_POST)) {
    file_put_contents (RESPONSE_FILE_NAME, json_encode($_POST));
    echo RESPONSE_URL;
    exit;
}

//####### 6. We read the post data from the response file (see RESPONSE_FILE_NAME constant), since this is the         #
//#######    customer session where we need the information.                                                           #
$params = json_decode(file_get_contents(RESPONSE_FILE_NAME), 1);

//####### 7. We creates a heidelpay response object from the post data to conveniently access the information we need. #
$response = Response::fromPost($params);

//####### 8. Show the customer the transaction result.                                                                 #
?>
<html>
<head>
	<title>Factoring example</title>
</head>
<body>
<?php
echo '<h1>Invoice Factoring example</h1>';
if ($response->isSuccess()) {
    $paymentCode = explode('.', $response->getPayment()->getCode());
    if($paymentCode[1] === 'PA') {
        echo '<strong>Reservation Sucessful: </strong>' . '</br>';
        echo 'The Customer journey ends here.' . '</br>';

        /** ####### 9. Here finalize the order directly after successful reservation. In your case this should happen
         * together with the shipping.
         */

        $basketData = [
            '2843294932',
            $response->getPresentation()->getAmount(),
            $response->getPresentation()->getCurrency(),
            '39542395235ßfsokkspreipsr'
        ];

        $invoicePaymentMethod = new InvoiceB2CSecuredPaymentMethod();
        $invoicePaymentMethod->getRequest()->basketData(...$basketData);
        $invoicePaymentMethod->getRequest()->authentification(
            HEIDELPAY_SECURITY_SENDER,  // SecuritySender
            HEIDELPAY_USER_LOGIN,  // UserLogin
            HEIDELPAY_USER_PASSWORD,  // UserPassword
            HEIDELPAY_TRANSACTION_CHANNEL,  // TransactionChannel
            true                                 // Enable sandbox mode
        );

        /**
         * Following steps are done by the Merchant:
         * Order was shipped and finalized automatically.
         * Insurance is active from now on.
         * Sometimes a reversal is necessary.
         */
        $invoicePaymentMethod->finalize($response->getPaymentReferenceId());
        $finalizeResponse = $invoicePaymentMethod->getResponse();
        if ($finalizeResponse->isSuccess() || $finalizeResponse->getError()['cod'] === '700.400.800') {
            echo"<p>Following steps are done by the Merchant: \n
                Usually the merchant finalizes the order with the shipping. In this example we did this automatically. <br/>
                Insurance is active from now on.
                </p>";

            echo '<p>Sometimes a reversal is necessary. This can be done here. <a href="'
                . REVERSAL_URL .'">Continue with Reversal</a>
                </p>';
        } else {
            echo '<pre>'. print_r($finalizeResponse->getError(), 1).'</pre>';
        }
    }

} else {
    echo '<pre>'. print_r($response->getError(), 1).'</pre>';
}
?>
</body>
</html>
