# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/) and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased][unreleased]
The following changes are planned for future releases.
- Make all payment method classes inherit the BasicPaymentMethodTrait.
- The bank property will be removed from AccountParameterGroup.
- The account_bank and account_number properties will be removed from ConnectorParameterGroup.
- The class AbstractPaymentMethod will be removed.
- Credit card payment will be refactored to mask credit card number, expiration date and security code.
- Code style issues will be fixed using [PhpStorm](https://www.jetbrains.com/phpstorm/) Plugin [Php Inspections (EA Extended)](https://plugins.jetbrains.com/plugin/7622-php-inspections-ea-extended-).



## [v1.1.0][v1.1.0]
### Fixed
- Code style issues.

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
- Needless parameters from registration()-method call in class "DirectDebitRegistration".

[unreleased]: https://github.com/heidelpay/php-payment-api/compare/v1.1.0...HEAD
[v1.1.0]: https://github.com/heidelpay/php-payment-api/compare/v1.0.0...v1.1.0
[v1.0.0]: https://github.com/heidelpay/php-payment-api/tree/v1.0.0