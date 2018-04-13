# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/) and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [v1.5.0][v1.5.0]
### Added
- New unit test to cover finalize payment method.

### Changed
- Replaced static strings with constants.

### Removed
- Finalize payment method and tests from direct debit secured.

### Fixed
- Code style issues.
- Overwriting of parameters when calling credit/debit card methods without parameters

## [v1.4.1][v1.4.1]
### Fixed
- Error in DirectDebitB2CSecured during integration test of reversal transaction.
- Fixed a bug which can cause errors due to irregular post parameters.

### Changed
- Renamed "Heidelberger Payment GmbH" to "heidelpay GmbH" due to re-branding.
- Changed tlds from de to com.
- Changed documentation and data-privacy-policy links.

## [v1.4.0][v1.4.0]
### Added
- Transaction type reregistration.
- Unit and integration tests for reregistration transaction.
- `toArray()` method to the AbstractMethod class for Request/Response
- Added important methods to the PaymentMethodInterface.

### Changed
- Added debug output to integration tests. Append `--debug` to see them (`codecept run integration --debug`).
- Replaced magic setters.

## [v1.3.0][v1.3.0]
### Added
- `fromJson()` and `fromPost()` static methods to instantiate `Response` and `Request` objects with a static call.
- Several constants for Api Config, Paymentmethod codes, status/reason codes, ... see the following classes in the `Heidelpay\PhpPaymentApi\Constants` namespace:
  - `ApiConfig` includes this sdk's version, live and test api urls
  - `Brand` includes codes for brands (e.g. Visa, giropay, PayPal)
  - `PaymentMethod` includes codes for all payment methods (e.g. CC for Credit Card, OT for Online Transfer, ...)
  - `ProcessingResult` includes transaction result codes (ACK and NOK for now)
  - `ReasonCode` includes reason codes (indicators for errors) of transactions
  - `StatusCode` includes transaction status codes
  - `TransactionMode` includes transaction modes, which are important for the running environment
  - `TransactionType` includes codes for transaction types (e.g. Capture, Debit, Reversal, ...)

### Changed
- Clearified the exception message in `verifySecurityHash()` (Response script/page should only be called by heidelpay)

### Removed
- `AbstractPaymentMethod` class in favor of the `BasicPaymentMethodTrait`

### Deprecated
- Declared `Response::splitArray()` as deprecated in favor of `fromPost()` and replaced it's code with a `fromPost` call


## [v1.2.0][v1.2.0]
### Added
- `HttpAdapterInterface` to allow injecting a custom http adapter.
- Criterion `get()` method for custom properties

### Changed
- Refactored Requests send method to only create a `CurlAdapter` object when needed.
- Refactored unit tests to inject a `CurlAdapter` object instead of an InterfaceProxy object.


## [v1.1.0][v1.1.0]
### Fixed
- Code style issues.
- Defined version pattern for phpunit coverage package to work around bug in codeception coverage package.

### Changed
- Package description, replace, conflict and badges.
- Replaced hard coded version with constant.


## [v1.0.0][v1.0.0]
### Added
- Added integration tests.
- Added new unit tests.
- Added payolution payment method.

### Fixed
- Fixed several code style issues.
- Fixed setting custom http-adapter.

### Changed
- Replaced Coveralls with Codacy as analytic tool.
- Reorganized Travis CI script.
- Introduced Codeception as unit test framework and replaced phpUnit.
- Refactored existing unit tests.
- Changed versioning to semantic versioning.
- Changed repository. 
- Changed namespaces to new repo name.

### Removed
- Needless parameters from `registration()`-method call in class `DirectDebitRegistration`.

[v1.0.0]: https://github.com/heidelpay/php-payment-api/tree/v1.0.0
[v1.1.0]: https://github.com/heidelpay/php-payment-api/compare/v1.0.0...v1.1.0
[v1.2.0]: https://github.com/heidelpay/php-payment-api/compare/v1.1.0...v1.2.0
[v1.3.0]: https://github.com/heidelpay/php-payment-api/compare/v1.2.0...v1.3.0
[v1.4.0]: https://github.com/heidelpay/php-payment-api/compare/v1.3.0...v1.4.0
[v1.4.1]: https://github.com/heidelpay/php-payment-api/compare/v1.4.0...v1.4.1
[v1.5.0]: https://github.com/heidelpay/php-payment-api/compare/v1.4.0...v1.5.0
