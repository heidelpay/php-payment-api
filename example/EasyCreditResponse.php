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
require_once './_enableExamples.php';

/**
 * Require the composer autoloader file
 */
require_once __DIR__ . '/../vendor/autoload.php';

if (!empty($_POST)) {
    echo HEIDELPAY_PHP_PAYMENT_API_URL . HEIDELPAY_PHP_PAYMENT_API_FOLDER . 'EasyCreditResponse.php';
//    mail('simon.gabriel@heidelpay.com', 'betreff', print_r($_POST,1));
    file_put_contents (EASY_CREDIT_RESPONSE_PARAMS_TXT, print_r($_POST, 1));
    exit();
}

$params = file_get_contents(EASY_CREDIT_RESPONSE_PARAMS_TXT);

echo print_r($params,1);

///**
// * Load a new instance of the payment method
// */
// $easyCredit = new EasyCreditPaymentMethod();
//
// /** @var Response $response */
// $response = Response::fromPost($_POST);

// var_dump($_POST);
// var_dump($response);
// var_dump($response->getCriterion()->get('EASYCREDIT_AMORTISATIONTEXT'));


//$easyCredit->initialize();
//
//?>
<!--<html>-->
<!--<head>-->
<!--	<title>EasyCredit example</title>-->
<!--</head>-->
<!--<body>-->
<?php
//$response = $easyCredit->getResponse();
//if ($response->isSuccess()) {
//        echo '<input type="checkbox" required value="true"/><p>';
//        echo $response->getConfig()->getOptinText();
//        echo'</p><a href="'. $response->getPaymentFormUrl().'">Weiter zu easyCredit...</a>';
//    } else {
//        echo '<pre>'. print_r($response->getError(), 1).'</pre>';
//    }
// ?>
<!-- <p>It is not necessary to show the redirect url to your customer. You can-->
<!-- use php header to forward your customer directly.<br/>-->
<!-- For example:<br/>-->
<!-- header('Location: '.$Invoice->getResponse()->getPaymentFromUrl());-->
<!-- </p>-->
<!-- </body>-->
<!-- </html>-->
