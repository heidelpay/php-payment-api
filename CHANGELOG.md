# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/).

## [Unreleased][unreleased]
The following changes are planned for future releases.
- Make all payment method classes inherit the BasicPaymentMethodTrait.
- The bank property will be removed from AccountParameterGroup.
- The account_bank and account_number properties will be removed from ConnectorParameterGroup.
- The class AbstractPaymentMethod will be removed.
- Credit card payment will be refactored to mask credit card number, expiration date and security code.
- Code style issues will be fixed using [PhpStorm](https://www.jetbrains.com/phpstorm/) Plugin [Php Inspections (EA Extended)](https://plugins.jetbrains.com/plugin/7622-php-inspections-ea-extended-).


## [v1.0.0][v1.0.0]
### Added
- Added integration tests.
- Added new unit tests.

### Fixed
- Fixed several code style issues.
- Fixed setting custom http-adapter.

### Changed
- Replaced Coveralls with Codacy as analytic tool.
- Reorganized Travis CI script.
- Introduced Codeception as unit test framework and replaced phpUnit.
- Refactored existing unit tests.

[unreleased]: https://github.com/heidelpay/php-api/compare/master...HEAD
[v1.0.0]: https://github.com/heidelpay/php-api/compare/refactor_tests_to_codeception...HEAD