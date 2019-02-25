[![Latest Version on Packagist](https://img.shields.io/packagist/v/heidelpay/php-payment-api.svg?style=flat-square)](https://packagist.org/packages/heidelpay/php-payment-api)
[![Build Status](https://travis-ci.org/heidelpay/php-payment-api.svg?branch=master)](https://travis-ci.org/heidelpay/php-payment-api)
[![Codacy Badge](https://api.codacy.com/project/badge/Coverage/b1678b370db5462781415cd8800d56f3)](https://www.codacy.com/app/heidelpay/php-payment-api?utm_source=github.com&utm_medium=referral&utm_content=heidelpay/php-payment-api&utm_campaign=Badge_Coverage)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/b1678b370db5462781415cd8800d56f3)](https://www.codacy.com/app/heidelpay/php-payment-api?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=heidelpay/php-payment-api&amp;utm_campaign=Badge_Grade)
[![PHP 5.6](https://img.shields.io/badge/php-5.6-blue.svg)](http://www.php.net)
[![PHP 7.0](https://img.shields.io/badge/php-7.0-blue.svg)](http://www.php.net)
[![PHP 7.1](https://img.shields.io/badge/php-7.1-blue.svg)](http://www.php.net)
[![PHP 7.1](https://img.shields.io/badge/php-7.2-blue.svg)](http://www.php.net)
![Logo](http://dev.heidelpay.com/devHeidelpay_400_180.jpg)

# Welcome to the heidelpay payment api for php

This is the php payment api for heidelpay. The library will help you to easily integrate heidelpay into your application.

## Currently supported payment methods:

* credit card
* debit card
* prepayment
* Sofort
* PayPal
* direct debit
* iDeal
* Giropay
* Przelewy24
* PostFinance Card
* PostFinance EFinance
* EPS
* invoice
* invoice secured b2c
* invoice secured b2b
* direct debit secured b2c
* Santander invoice
* Santander hire purchase
* easyCredit
* Payolution invoice

### SYSTEM REQUIREMENTS

php-payment-api requires PHP 5.6 or higher; we recommend using the latest stable PHP version whenever possible.

## SECURITY ADVICE
If you want to store the output of this library e.g. into a database, please make sure that your
application takes care of sql injection, cross-site-scripting (xss) and so on. There is currently no build-in protection.

## LICENSE

You can find a copy of this license in [LICENSE.txt](LICENSE.txt).

## Support
For any issues or questions please get in touch with our support.

#### Web page
https://dev.heidelpay.com/
 
#### Email
support@heidelpay.com
 
#### Phone
+49 (0)6221/6471-100

#### Twitter
@devHeidelpay

## Documentation

Please visit [http://dev.heidelpay.com/heidelpay-php-payment-api/](http://dev.heidelpay.com/heidelpay-php-payment-api/) for the developer documentation.

### Unit- and Integration tests

This library comes with a set of unit and integration tests. Please do not run the integration tests on each build.

Run prior to tests:
`vendor/bin/codecept build`

Run unit tests:
`vendor/bin/codecept run unit`

Run unit tests with code coverage report:
`vendor/bin/codecept run unit --coverage --coverage-html`

Run integration tests:
`vendor/bin/codecept run integration`

Run integration tests with debug output:
`vendor/bin/codecept run integration --debug`

Run all integration test of a specific class:
`vendor/bin/codecept run tests/integration/PaymentMethods/EasyCreditPaymentMethodTest`

Run a specific integration test:
`vendor/bin/codecept run tests/integration/PaymentMethods/EasyCreditPaymentMethodTest:initialRequest`

For coverage analysis results see:
`./tests/_output/coverage/index.html`

### Examples

Integration examples can be found in the example folder. Just open the

`_enableExamples.php` and change

`define('HEIDELPAY_PHP_PAYMENT_API_EXAMPLES', FALSE);`

to

`define('HEIDELPAY_PHP_PAYMENT_API_EXAMPLES', TRUE);`

Please make sure to switch it off again, after you launch your application.

To enable the easyCredit example you might have to change the file permissions for the file "example/EasyCredit/EasyCreditResponseParams.txt".\
It will be used to store the post-parameters send asynchronously by the payment which otherwise would not be accessable in the client session.
