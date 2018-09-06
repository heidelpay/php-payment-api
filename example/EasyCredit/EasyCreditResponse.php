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

const EASY_CREDIT_RESPONSE_PARAMS_TXT = __DIR__ . '/EasyCreditResponseParams.txt';
require_once './../_enableExamples.php';

/**
 * Require the composer autoloader file
 */
require_once __DIR__ . '/../../vendor/autoload.php';

if (!empty($_POST)) {
    $responseURL = HEIDELPAY_PHP_PAYMENT_API_URL . HEIDELPAY_PHP_PAYMENT_API_FOLDER . 'EasyCredit/EasyCreditResponse.php';
    echo $responseURL;
    file_put_contents (EASY_CREDIT_RESPONSE_PARAMS_TXT, json_encode($_POST));
    exit();
}
$reservationURL = HEIDELPAY_PHP_PAYMENT_API_URL . HEIDELPAY_PHP_PAYMENT_API_FOLDER . 'EasyCredit/EasyCreditReservation.php';

$params = json_decode(file_get_contents(EASY_CREDIT_RESPONSE_PARAMS_TXT),1);

$response = Response::fromPost($params);

?>
<html>
<head>
	<title>EasyCredit example</title>
</head>
<body>
<?php
//$response = $easyCredit->getResponse();
if ($response->isSuccess()) {
    echo '<strong>Hier Ihr ausgewählter Ratenplan: </strong>' . '</br>';
    $amortisationtext = $response->getCriterion()->get('EASYCREDIT_AMORTISATIONTEXT');
    $precontractInformationUrl = $response->getCriterion()->get('EASYCREDIT_PRECONTRACTINFORMATIONURL');
    echo $amortisationtext  . '</br></br>';
    echo '<a href="' . $precontractInformationUrl . '" target="_blank">Ihre vorvertraglichen Informationen zum Download...</a></br>';
    echo 'Rechnungsbetrag: ' . $response->getCriterion()->get('EASYCREDIT_TOTALORDERAMOUNT') . '</br>';
    echo 'Zinsen: '. $response->getCriterion()->get('EASYCREDIT_ACCRUINGINTEREST') . '</br>';
    echo 'Gesamt inkl. Zinsen: '. $response->getCriterion()->get('EASYCREDIT_TOTALAMOUNT') . '</br>';

    echo'</p><a href="'. $reservationURL .'">Zahlungspflichtig bestellen...</a>';
} else {
    echo '<pre>'. print_r($response->getError(), 1).'</pre>';
}
?>
 <p>It is not necessary to show the redirect url to your customer. You can
 use php header to forward your customer directly.<br/>
 For example:<br/>
 header('Location: '.$Invoice->getResponse()->getPaymentFromUrl());
 </p>
 </body>
 </html>
