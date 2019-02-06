<?php
namespace Heidelpay\Example\PhpPaymentApi;

/**
 * Handles response for Santander hire Purchase example
 *
 * This is a coding example for invoice authorize using heidelpay php-payment-api
 * extension.
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-payment-api/
 *
 * @author  Sascha Pflüger
 *
 * @category example
 */

use Heidelpay\PhpPaymentApi\Response;

//#######   Checks whether examples are enabled. #######################################################################
require_once __DIR__ . '/SantanderHpConstants.php';

/**
 * Require the composer autoloader file
 */

$path = __DIR__;
$splitpath = str_replace('heidelpay/php-payment-api/example/SantanderHp',"",$path);
require_once $splitpath . '/autoload.php';

//####### 4. This page is called in a server-to-server request as soon as the customer selected a payment plan.        #
//####### 6. This page will be called a second time when the customer is redirected back to the "shop" which in reality#
//#######    would probably be a different page. Since this will be done without any POST-data the next step will be #7#

//####### 5. The server-to-server request after the payment plan selection will provide the selected information       #
//#######       within a PDF in CRITERION.SANTANDER_HP_PDF_URL as post data.                                           #
//#######    In this request we will two things:                                                                       #
//#######       1.  We will store the post data withing the reponse file (see RESPONSE_FILE_NAME constant) to make it  #
//#######           available in the customer session.                                                                 #

if (!empty($_POST)) {
    file_put_contents (RESPONSE_FILE_NAME, json_encode($_POST));
    echo RESPONSE_URL;
    exit;
}

//####### 7. We read the post data from the response file (see RESPONSE_FILE_NAME constant), since this is the         #
//#######    customer session where we need the information.                                                           #
$params = json_decode(file_get_contents(RESPONSE_FILE_NAME), 1);

//####### 8. We creates a heidelpay response object from the post data to conveniently access the information we need. #
$response = Response::fromPost($params);

//####### 9. Show the customer the precontract information to donwload as a PDF-file. ##################################
//#######    It is necessary to show the information again to the customer prior to him placing the order.             #
//#######    After the customer places the order we will send a reservation command to our payment server.             #
//#######    For the next step see the file defined with the RESERVATION_URL constant.                                 #
?>
<html>
<head>
	<title>Santander HP example</title>
</head>
<body>
<?php
echo '<h1>Santander Hire Purchase example</h1>';
if ($response->isSuccess()) {
    echo '<strong>Please approve your payment plan: </strong>' . '</br>';
    echo '<a href="' . $response->getCriterion()->get('SANTANDER_HP_PDF_URL') . '" target="_blank">Download the precontract information here...</a></br>';
    echo'</p><a href="'. RESERVATION_URL .'">Place order...</a>';
} else {
    echo '<pre>'. print_r($response->getError(), 1).'</pre>';
}
?>
</body>
</html>
