<?php
namespace Heidelpay\Example\PhpPaymentApi;

/**
 * Invoice b2c secured authorize example
 *
 * This is a coding example for invoice b2c secured authorize using heidelpay php-payment-api
 * extension.
 *
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-payment-api/
 *
 * @author  David Owusu  <development@heidelpay.com>
 *
 * @category example
 */

/**
 * For security reason all examples are disabled by default.
 */

use Heidelpay\PhpPaymentApi\Constants\CommercialSector;
use Heidelpay\PhpPaymentApi\Constants\RegistrationType;
use Heidelpay\PhpPaymentApi\PaymentMethods\InvoiceB2BSecuredPaymentMethod;

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
$invoice = new InvoiceB2BSecuredPaymentMethod();

/**
 * Set up your authentification data for Heidepay api
 *
 * @link https://dev.heidelpay.com/testumgebung/#Authentifizierungsdaten
 */
$invoice->getRequest()->authentification(
    '31HA07BC8142C5A171745D00AD63D182',  // SecuritySender
    '31ha07bc8142c5a171744e5aef11ffd3',  // UserLogin
    '93167DE7',                          // UserPassword
    '31HA07BC8129FBA7AF655AB2C27E5B3C',  // TransactionChannel credit card without 3d secure
    true                                 // Enable sandbox mode
);
/**
 * Set up asynchronous request parameters
 */
$invoice->getRequest()->async(
    'EN', // Language code for the Frame
    HEIDELPAY_PHP_PAYMENT_API_URL .
    HEIDELPAY_PHP_PAYMENT_API_FOLDER .
    'HeidelpayResponse.php' // Response url from your application
);

/**
 * Set up customer information required for risk checks
 */
$invoice->getRequest()->customerAddress(
    'Heidel',                  // Given name
    'Berger-Payment',           // Family name
    null,                     // Company Name
    '12344',                   // Customer id of your application
    'Vangerowstr. 18',          // Billing address street
    null,                   // Billing address state
    '69115',                   // Billing address post code
    'Heidelberg',              // Billing address city
    'DE',                      // Billing address country code
    'support@heidelpay.com'     // Customer mail address
);

/**
 * Set up basket or transaction information
 */
$invoice->getRequest()->basketData(
    '2843294932', // Reference Id of your application
    round(23.199, 2),                         // Amount of this request
    'EUR',                         // Currency code of this request
    '39542395235ßfsokkspreipsr'    // A secret passphrase from your application
);

/**
 * Set up company information necessary for B2B.
 */
$companyArray = [
    'heidelpay GmbH',               // Company name
    null,                           // Address postbox
    'Vangerowstr. 18',              // Address street
    '69115',                        // Address zip
    'Heidelberg',                   // Address city
    'DE',                           // Address country
    CommercialSector::AIR_TRANSPORT, // CommercialSector provides
    RegistrationType::NOT_REGISTERED, // Registration Type
    '123123',    // commercial register number. Necessary if registration type is "REGISTERED"
    '123456'        // Vat id
];

/**
 * Set up executive Information. Executive is necessary if registration type is "NOT_REGISTERED"
 */
$executive = [
    'Herr',             // Salutation
    'Testkäufer',       // Given name
    'Händler',          // Family name
    '1988-12-12',       // Birthdate
    'example@email.de', // Email address
    '062216471400',     // Phone number
    'Vangerowstr. 18',  // Address street
    '69115',            // Address zip
    'Heidelberg',       // Address city
    'DE',               // Address country
];

/**
 * Set request data für B2B.
 */
$invoice->getRequest()->company(...$companyArray);
$invoice->getRequest()->getCompany()->addExecutive(...$executive);

/**
 * Set necessary parameters for heidelpay payment and send the request
 */
$invoice->authorize();

?>
<html>
<head>
    <title>Invoice authorize example</title>
</head>
<body>
<?php
if ($invoice->getResponse()->isSuccess()) {
    echo '<a href="' . $invoice->getResponse()->getPaymentFormUrl() . '">place Invoice</a><br/>';
} else {
    echo '<pre>' . print_r($invoice->getResponse()->getError(), 1) . '</pre>';
}
?>
<p>It is not necessary to show the redirect url to your customer. You can
    use php header to forward your customer directly.<br/>
    For example:<br/>
    header('Location: '.$Invoice->getResponse()->getPaymentFromUrl());
</p>
<p>Note: If you can not provide the customer details for secured invoice form your
    application. You can build a form using getPaymentFormUrl() and provide NAME.SALUTATION
    and NAME.BIRTDATE as input fields.
</p>
</body>
</html>
 
 
 
