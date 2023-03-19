# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.3.0] - 2023-03-19
### Added
- Extracting metadata from video measurements by [@kryst3q](https://github.com/kryst3q)

## [1.2.0] - 2023-03-12
### Added
- Uploading video measurements in AVI format by [@kryst3q](https://github.com/kryst3q)

## [1.1.0] - 2023-03-10
### Added
- Possibility of changing user password by [@kryst3q](https://github.com/kryst3q)

## [1.0.1] - 2022-12-22
### Changed
- Content type of adding measurement to multipart/form-data by [@kryst3q](https://github.com/kryst3q)
- Names of properties in adding measurement request by [@kryst3q](https://github.com/kryst3q)

### Fixed
- Endpoint providing JSON OpenAPI documentation by [@kryst3q](https://github.com/kryst3q)

## [0.47.1] - 2022-12-21
### Fixed
- Retrieving measurements list when some measurements don't have name by [@kryst3q](https://github.com/kryst3q)

## [0.47.0] - 2022-11-18
### Changed
- Sending account activation email asynchronously by [@kryst3q](https://github.com/kryst3q)

## [0.46.0] - 2022-11-11
### Changed
- Way of handling incoming commands by using Symfony Messenger by [@kryst3q](https://github.com/kryst3q)

## [0.45.0] - 2022-10-23
### Added
- Returning "application/json" content type header on error by [@kryst3q](https://github.com/kryst3q)

## [0.44.3] - 2022-10-23
### Fixed
- Activating registered account through an endpoint provided in an email by [@kryst3q](https://github.com/kryst3q)

## [0.44.2] - 2022-10-21
### Fixed
- Failing tests by [@kryst3q](https://github.com/kryst3q)
- Registration process by [@kryst3q](https://github.com/kryst3q)

### Changed
- Way of installing (in Docker image build phase) and storing (in container only) composer dependencies in dev env by [@kryst3q](https://github.com/kryst3q)

## [0.43.0] - 2022-10-19
### Added
- "ext-intl" as the requirement in composer.json file by [@kryst3q](https://github.com/kryst3q)

## [0.42.2] - 2022-10-18
### Fixed
- Setting up twig as dev and test requirement only by [@kryst3q](https://github.com/kryst3q)
- Value for USER_ACTIVATION_LINK_URL dev env variable by [@kryst3q](https://github.com/kryst3q)
- Move symfony/http-client dependency from require-dev to require (needed by password checker) by [@kryst3q](https://github.com/kryst3q)

## [0.42.0] - 2022-10-16
### Added
- Validating if user activation link didn't expired by [@kryst3q](https://github.com/kryst3q)

## [0.41.0] - 2022-10-16
### Added
- Endpoint to activate freshly registered user account by [@kryst3q](https://github.com/kryst3q)

## [0.40.0] - 2022-10-16
### Added
- User registration functionality by [@kryst3q](https://github.com/kryst3q)
- Symfony debug tools for improving development performance by [@kryst3q](https://github.com/kryst3q)

## [0.39.0] - 2022-10-06
### Added
- Library to handle CORS requests by [@kryst3q](https://github.com/kryst3q)
### Updated
- Testing framework (codeception) to the newest stable version (5.0.3) by [@kryst3q](https://github.com/kryst3q)

## [0.38.0] - 2022-10-06
### Added
- Info about logged in user in the response of POST `/api/login_check` endpoint by [@kryst3q](https://github.com/kryst3q)

## [0.37.0] - 2022-09-26
### Added
- API documentation according to [OpenAPI](https://swagger.io/docs/specification/about/) standard using [NelmioApiDocBundle](https://github.com/nelmio/NelmioApiDocBundle) library by [@kryst3q](https://github.com/kryst3q)

## [0.36.0] - 2022-08-09
### Added
- [Markdown Any Decision Record](https://adr.github.io/madr/) by [@kryst3q](https://github.com/kryst3q) to document project decisions

## [0.35.0] - 2022-08-09
### Added 
- Readme file by [@kryst3q](https://github.com/kryst3q)

## [0.34.0] - 2022-08-09
### Added
- Changelog file by [@kryst3q](https://github.com/kryst3q)
