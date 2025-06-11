# square-release-notes-rss

square-release-notes-rss is an unofficial RSS feed that publishes release notes for the [Square Developer platform](https://developer.squareup.com/). This project hosts the release notes of the Square Developer platform as an RSS feed on GitHub Pages.

## Available RSS Feeds

- Square APIs and SDKs
  - Official release notes: https://developer.squareup.com/docs/changelog/connect
  - RSS feed: https://meihei3.github.io/square-release-notes-rss/rss/square-apis-and-sdks.xml
- Mobile SDKs
  - Official release notes: https://developer.squareup.com/docs/changelog/mobile
  - RSS feed: https://meihei3.github.io/square-release-notes-rss/rss/mobile-sdks.xml
- Web Payments SDK
  - Official release notes: https://developer.squareup.com/docs/changelog/webpaymentsdk
  - RSS feed: https://meihei3.github.io/square-release-notes-rss/rss/webpaymentsdk.xml
- Payment Form
  - Official release notes: https://developer.squareup.com/docs/changelog/paymentform
  - RSS feed: https://meihei3.github.io/square-release-notes-rss/rss/paymentform.xml
- Requirements
  - Official release notes: https://developer.squareup.com/docs/changelog/requirements
  - RSS feed: https://meihei3.github.io/square-release-notes-rss/rss/requirements.xml

## Project Overview

This project automatically fetches release notes from the Square Developer platform, converts them to RSS format, and publishes them to GitHub Pages. It uses Symfony components for the core functionality and includes comprehensive tests to ensure reliability.

## Installation

### Requirements

- PHP 8.4 or higher
- Composer

### Setup

1. Clone the repository:
   ```bash
   git clone https://github.com/meihei3/square-release-notes-rss.git
   cd square-release-notes-rss
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

## Usage

To fetch the latest release notes and generate RSS feeds:

```bash
php bin/console retrieve-square-change-log
```

This command will:
1. Fetch the latest release notes from Square's developer website
2. Compare them with previously stored release notes
3. Update the JSON files if there are changes
4. Generate new RSS feeds

## Testing

The project includes comprehensive tests to ensure functionality. The tests are organized into:

- Unit tests for individual components
- Functional tests for application features

### Running Tests

You can run the tests using Composer:

```bash
# Run all tests
composer test

# Run tests with code coverage report
composer test-coverage
```

For more details about the tests, see the [tests/README.md](tests/README.md) file.
