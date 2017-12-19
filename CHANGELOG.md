# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/) and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).


## [v1.3.0][v1.3.0]
## Added
- `fromJson()` and `fromPost()` static methods to instantiate Response and Request objects with a static call.
- `RESULT_ACK` and `RESULT_NOK` constants inside of `ProcessingParameterGroup`

## Changed
- Declared `Response::splitArray()` as deprecated in favor of `fromPost()`.
- Clearified the exception message in `verifySecurityHash()` (Response script/page should only be called by heidelpay)


## [v1.2.0][v1.2.0]
## Added
- `HttpAdapterInterface` to allow injecting a custom http adapter.
- Criterion `get()` method for custom properties

## Changed
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

[v1.3.0]: https://github.com/heidelpay/php-payment-api/compare/v1.2.0...v1.3.0
[v1.2.0]: https://github.com/heidelpay/php-payment-api/compare/v1.1.0...v1.2.0
[v1.1.0]: https://github.com/heidelpay/php-payment-api/compare/v1.0.0...v1.1.0
[v1.0.0]: https://github.com/heidelpay/php-payment-api/tree/v1.0.0