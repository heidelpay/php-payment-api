[![Coverage Status](https://coveralls.io/repos/github/heidelpay/php-api/badge.svg?branch=develop)](https://coveralls.io/github/heidelpay/php-api?branch=develop)
[![Build Status](https://travis-ci.org/heidelpay/php-api.svg?branch=master)](https://travis-ci.org/heidelpay/php-api)
![Logo](https://dev.heidelpay.de/devHeidelpay_400_180.jpg)

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

### SYSTEM REQUIREMENTS

php-api requires PHP 5.6 or higher; we recommend using the
latest stable PHP version whenever possible.

## SECURITY ADVICE
If you want to store the output of this library into a database or something, please make sure that your
application takes care of sql injection, cross-site-scripting (xss) and so on. There is no build in protection
by now.

## LICENSE

You can find a copy of this license in [LICENSE.txt](LICENSE.txt).

## Documentation

Please visit https://dev.heidelpay.de/PhpApi/ for the developer documentation.

### UnitTest

This library comes with a set of unit tests. Please be so kind and do not run all tests on any build.  

### Examples

Integration examples can be found in the example folder. Just open the

_enableExamples.php and change

define('HeidelpayPhpApiExamples', FALSE);

to

define('HeidelpayPhpApiExamples', TRUE);

Please make sure to switch it off again, after you lunch your application.

