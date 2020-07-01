<?php
/**
 * This example shows an example implementation for the giropay payment type WITHOUT bank selection in the shop
 * but with redirect to the bank selection page providing the selection there.
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2019-present heidelpay GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-payment-api/
 *
 * @author  Simon Gabriel
 *
 * @category example
 */
namespace Heidelpay\Example\PhpPaymentApi;

/**
 * For security reason all examples are disabled by default.
 */
use Heidelpay\PhpPaymentApi\PaymentMethods\GiropayPaymentMethod;

require_once './_enableExamples.php';
if (defined('HEIDELPAY_PHP_PAYMENT_API_EXAMPLES') && HEIDELPAY_PHP_PAYMENT_API_EXAMPLES !== true) {
    exit();
}

/** Require the composer autoloader file */
require_once __DIR__ . '/../../../autoload.php';

/** create a new instance of the payment method */
$giropay        = new GiropayPaymentMethod();
$giropayRequest = $giropay->getRequest();

/**
  * Set up your authentication data for heidelpay api
  *
  * @link https://dev.heidelpay.com/testumgebung/#Authentifizierungsdaten
  */
$giropayRequest->authentification(
    '31HA07BC8142C5A171745D00AD63D182',     // SecuritySender
    '31ha07bc8142c5a171744e5aef11ffd3',        // UserLogin
    '93167DE7',                             // UserPassword
    '31HA07BC8142C5A171740166AF277E03', // TransactionChannel
    true                                  // enable/disable sandbox mode
);

$giropayRequest->getFrontend()->setResponseUrl('http://technik.heidelpay.de/jonas/responseAdvanced/response.php');

// set up customer information required for risk checks
$giropayRequest->customerAddress(
    'Hans',                   // Given name
    'Zimmer',                 // Family name
    null,                     // Company Name
    '12344',                  // Customer id of your application
    'Salzachstraße 13',       // Billing address street
    '',
    '1200',                   // Billing address post code
    'Wien',                   // Billing address city
    'AT',                     // Billing address country code
    'support@heidelpay.com'   // Customer mail address
    );

// set up basket or transaction information
$giropayRequest->basketData(
    '2843294932', // Reference Id of your application
    23.12,                         // Amount of this request
    'EUR',                         // Currency code of this request
    '39542395235ßfsokkspreipsr'    // A secret passphrase from your application
    );

// set bank country
$giropayRequest->getAccount()->setCountry('DE');

// disable frontend mode -> this shows that there is no additional input necessary by the customer prior to the redirect to the bank selection page.
$giropayRequest->getFrontend()->setEnabled('FALSE');

// perform the authorize transaction to retrieve the redirect url and parameters
$giropay->authorize();

// get the redirect url and necessary parameters from the response.
$redirectGroup  = $giropay->getResponse()->getProcessing()->getRedirect();
$redirectUrl    = $redirectGroup->getUrl();
$redirectParams = $redirectGroup->getParameter();

echo '<strong>RedirectUrl:</strong> ' . $redirectUrl;
ksort($redirectParams);
foreach($redirectParams as $key=>$value) {
    echo '<br><strong>RedirectParam</strong> ' . $key . ': ' . $value;
}

/**
 * Attention:
 * Unfortunately Sandbox-mode and Live-mode have to be handled differently.
 * In case of ..
 * 1. .. a sandbox transaction no redirect parameters exist and you have to perform a GET-Redirect to the redirect url.
 * 2. .. a live mode transaction there will be redirect parameters and you have to submit them via POST-request to the redirect url.
 *
 * The following example shows one possible solution to this task.
 */
?>

<html>
    <head>
        <title>Giropay authorize example</title>
    </head>

    <body>
        <!-- create a form which contains any parameters needed for the redirect -->
        <form id="payment-form" action="<?php echo $redirectUrl; ?>" method="post">
            <?php
                foreach ($redirectParams as $key=>$param) {
                    echo '<input hidden="true" name="' . $key . '" value="'. $param . '"/>';
                }
            ?>
            <button type="submit">Pay</button>
        </form>

        <script>
            var form = document.getElementById('payment-form');
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                // get the number ob redirect parameters
                var redirectParameterCount = form.getElementsByTagName('input').length;
                if (redirectParameterCount === 0) {
                    // ... perform a get redirect if there are no parameters needed for the redirect ==> Sandbox  mode
                    window.location.href = form.getAttribute('action');
                } else {
                    // ... submit the form via post with all redirect parameters ==> Live mode
                    form.submit();
                }
            });
        </script>
    </body>
</html>
