# Tests for Square Release Notes RSS

This directory contains tests for the Square Release Notes RSS application.

## Test Structure

- `Unit/`: Unit tests for individual components
  - `Lib/Implements/`: Tests for implementation classes
    - `ChangelogHistoryJsonFileStoreTest.php`: Tests for JSON file storage
    - `ChangelogHistoryRSSBuilderTest.php`: Tests for RSS feed generation
    - `SquareReleaseNotesFetchClientTest.php`: Tests for fetching changelog data
- `Functional/`: Functional tests for application features
  - `Command/`: Tests for console commands
    - `RetrieveSquareChangeLogCommandTest.php`: Tests for the main command
- `Fixtures/`: Test fixtures and sample data
  - `ChangelogHistoryFixtures.php`: Sample changelog data for tests

## Running Tests

You can run the tests using Composer:

```bash
# Run all tests
composer test

# Run tests with code coverage report
composer test-coverage
```

The code coverage report will be generated in the `.phpunit.coverage.html` directory.

## Test Coverage

The tests cover the following functionality:

### ChangelogHistoryJsonFileStore
- Storing and loading changelog histories for different Square products
- Error handling for file operations

### ChangelogHistoryRSSBuilder
- Building RSS feeds for different Square products
- Proper template rendering with correct parameters
- Truncation of long descriptions

### SquareReleaseNotesFetchClient
- Fetching changelog data from Square's developer website
- Parsing HTML and extracting JSON data
- Sorting changelog histories by date

### RetrieveSquareChangeLogCommand
- Integration of the three main services
- Updating files and building RSS feeds when there are changes
- Skipping updates when there are no changes