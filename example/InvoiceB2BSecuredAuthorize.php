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
 * @author  Jens Richter
 *
 * @category example
 */

/**
 * For security reason all examples are disabled by default.
 */

use Heidelpay\PhpPaymentApi\Constants\CommercialSector;
use Heidelpay\PhpPaymentApi\Constants\RegistrationType;
use Heidelpay\PhpPaymentApi\ParameterGroups\ExecutiveParameterGroup;
use Heidelpay\PhpPaymentApi\ParameterGroups\HomeParameterGroup;
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
    '31HA07BC8181E8CCFDAD64E8A4B3B766',  // SecuritySender
    '31ha07bc8181e8ccfdad73fd513d2a53',  // UserLogin
    '4B2D4BE3',                          // UserPassword
    '31HA07BC8122B949FB64075AFB0798AE',  // TransactionChannel credit card without 3d secure
    true                                 // Enable sandbox mode
);
/**
 * Set up asynchronous request parameters
 */
$invoice->getRequest()->async(
    'EN', // Language code for the Frame
    HEIDELPAY_PHP_PAYMENT_API_URL .
    HEIDELPAY_PHP_PAYMENT_API_FOLDER .
    'InvoiceB2BSecuredAuthorize.php'  // Response url from your application
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

$home = new HomeParameterGroup();
$home->street = 'Vangerowstr. 18';
$home->city = 'Heidelberg';
$home->country = 'DE';
$home->zip = '69115';

$executiveOne = [
    'OWNER',
    null,
    'Testkäufer',
    'Händler',
    '1988-12-12',
    'example@email.de',
    '062216471400',
    $home
];
$executiveTwo = [
    'OWNER',
    null,
    'Testkäufer',
    'Händler',
    '1988-12-12',
    'example@email.de',
    '062216471400',
    $home
];


$companyArray = [
    'heidelpay GmbH',
    null,
    'Vangerowstr. 18',
    '69115',
    'Heidelberg',
    'DE',
    CommercialSector::AIR_TRANSPORT,
    RegistrationType::REGISTERED,
    '123456789',
    '123456'
];

$invoice->getRequest()->company(...$companyArray);
$invoice->getRequest()->addExecutive(...$executiveOne);
$invoice->getRequest()->addExecutive(...$executiveTwo);

/**
 * Set necessary parameters for Heidelpay payment and send the request
 */
$invoice->authorize();
//echo print_r($invoice->getResponse(), 1);
echo print_r($invoice->getResponse(), 1) . '\n';

?>
<html>
<head>
    <title>Invoice authorize example</title>
</head>
<body>
<?php
if ($invoice->getResponse()->isSuccess()) {
    echo '<a href="' . $invoice->getResponse()->getPaymentFormUrl() . '">place Invoice</a><br/>';
    //echo print_r($invoice->getResponse(), 1);
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
 
 
 