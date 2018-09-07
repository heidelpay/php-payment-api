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

//#######   Checks whether examples are enabled. #######################################################################
require_once __DIR__ . '/EasyCreditConstants.php';

/**
 * Require the composer autoloader file
 */
require_once __DIR__ . '/../../vendor/autoload.php';

//####### 4. This page is called in a server-to-server request as soon as the customer selected a payment plan.        #
//####### 6. This page will be called a second time when the customer is redirected back to the "shop" which in reality#
//#######    would probably be a different page. Since this will be done without any POST-data the next step will be #7#

//####### 5. The server-to-server request after the payment plan selection will provide the selected information       #
//#######    as post data.                                                                                             #
//#######    In this request we will two things:                                                                       #
//#######       1.  We will store the post data withing the reponse file (see RESPONSE_FILE_NAME constant) to make it  #
//#######           available in the customer session.                                                                 #
//#######       2.  We will provide the redirectUrl the customer will be redirected to. It is crutial to just return   #
//#######           the Url and nothing else, no html tags no comments, no newlines, just the url.                     #
if (!empty($_POST)) {
    file_put_contents (RESPONSE_FILE_NAME, json_encode($_POST));
    echo RESPONSE_URL;
    exit;
}

//####### 7. We read the post data from the response file (see RESPONSE_FILE_NAME constant), since this ist the        #
//#######    customer session where we need the information.                                                           #
$params = json_decode(file_get_contents(RESPONSE_FILE_NAME), 1);

//####### 8. We creates a heidelpay response object from the post data to conveniently access the information we need. #
$response = Response::fromPost($params);

//####### 9. This renders the information on the payment plan sent by easyCredit. ######################################
//#######    It is necessary to show the information again to the customer prior to him placing the order.             #
//#######    The rendered information consists of...
//#######       1. ... the amortisation text
//#######       2. ... a link to download precontract information
//#######       3. ... the order total
//#######       4. ... the interest due to the payment plan
//#######       5. ... the total incl. the interest
//#######    After that the customer can place the order which will send a reservation command to our payment server. ##
//#######    For the next step see the file defined with the RESERVATION_URL constant.
?>
<html>
<head>
	<title>EasyCredit example</title>
</head>
<body>
<?php
echo '<h1>EasyCredit example</h1>';
if ($response->isSuccess()) {
    echo '<strong>Please approve your payment plan: </strong>' . '</br>';
    $amortisationtext = $response->getCriterion()->get('EASYCREDIT_AMORTISATIONTEXT');
    $precontractInformationUrl = $response->getCriterion()->get('EASYCREDIT_PRECONTRACTINFORMATIONURL');
    echo $amortisationtext  . '</br></br>';
    echo '<a href="' . $precontractInformationUrl . '" target="_blank">Download the precontract information here...</a></br>';
    echo 'Order total: ' . $response->getCriterion()->get('EASYCREDIT_TOTALORDERAMOUNT') . '</br>';
    echo 'Interest: '. $response->getCriterion()->get('EASYCREDIT_ACCRUINGINTEREST') . '</br>';
    echo 'Total incl. interest: '. $response->getCriterion()->get('EASYCREDIT_TOTALAMOUNT') . '</br>';

    echo'</p><a href="'. RESERVATION_URL .'">Place order...</a>';
} else {
    echo '<pre>'. print_r($response->getError(), 1).'</pre>';
}
?>
</body>
</html>
