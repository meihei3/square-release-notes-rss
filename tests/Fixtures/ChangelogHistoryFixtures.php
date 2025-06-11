<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Lib\ChangelogHistory;

final class ChangelogHistoryFixtures
{
    /**
     * @return list<ChangelogHistory>
     */
    public static function createSampleChangelogHistories(): array
    {
        return [
            new ChangelogHistory(
                id: 'changelog-1',
                slug: 'changelog/webpaymentsdk/2023-05-01',
                linkBack: 'https://developer.squareup.com/docs/changelog/webpaymentsdk/2023-05-01',
                changelogType: 'Web Payments SDK',
                changelogDate: '2023-05-01',
                summary: 'Web Payments SDK 1.0.0 released',
                details: 'Initial release of the Web Payments SDK with support for credit card payments.',
                tags: ['web', 'payments', 'sdk', 'release'],
            ),
            new ChangelogHistory(
                id: 'changelog-2',
                slug: 'changelog/webpaymentsdk/2023-06-15',
                linkBack: 'https://developer.squareup.com/docs/changelog/webpaymentsdk/2023-06-15',
                changelogType: 'Web Payments SDK',
                changelogDate: '2023-06-15',
                summary: 'Web Payments SDK 1.1.0 released',
                details: 'Added support for Apple Pay and Google Pay. Fixed various bugs.',
                tags: ['web', 'payments', 'sdk', 'update'],
            ),
            new ChangelogHistory(
                id: 'changelog-3',
                slug: 'changelog/webpaymentsdk/2023-07-30',
                linkBack: 'https://developer.squareup.com/docs/changelog/webpaymentsdk/2023-07-30',
                changelogType: 'Web Payments SDK',
                changelogDate: '2023-07-30',
                summary: 'Web Payments SDK 1.2.0 released',
                details: 'This release includes performance improvements and adds support for new payment methods. ' .
                         'The SDK now loads faster and has a smaller footprint. ' .
                         'We\'ve also improved error handling and added better documentation.',
                tags: ['web', 'payments', 'sdk', 'performance'],
            ),
        ];
    }

    /**
     * @return string
     */
    public static function getSampleJsonContent(): string
    {
        return <<<JSON
[
    {
        "id": "changelog-1",
        "slug": "changelog/webpaymentsdk/2023-05-01",
        "linkBack": "https://developer.squareup.com/docs/changelog/webpaymentsdk/2023-05-01",
        "changelogType": "Web Payments SDK",
        "changelogDate": "2023-05-01",
        "summary": "Web Payments SDK 1.0.0 released",
        "details": "Initial release of the Web Payments SDK with support for credit card payments.",
        "tags": ["web", "payments", "sdk", "release"]
    },
    {
        "id": "changelog-2",
        "slug": "changelog/webpaymentsdk/2023-06-15",
        "linkBack": "https://developer.squareup.com/docs/changelog/webpaymentsdk/2023-06-15",
        "changelogType": "Web Payments SDK",
        "changelogDate": "2023-06-15",
        "summary": "Web Payments SDK 1.1.0 released",
        "details": "Added support for Apple Pay and Google Pay. Fixed various bugs.",
        "tags": ["web", "payments", "sdk", "update"]
    },
    {
        "id": "changelog-3",
        "slug": "changelog/webpaymentsdk/2023-07-30",
        "linkBack": "https://developer.squareup.com/docs/changelog/webpaymentsdk/2023-07-30",
        "changelogType": "Web Payments SDK",
        "changelogDate": "2023-07-30",
        "summary": "Web Payments SDK 1.2.0 released",
        "details": "This release includes performance improvements and adds support for new payment methods. The SDK now loads faster and has a smaller footprint. We've also improved error handling and added better documentation.",
        "tags": ["web", "payments", "sdk", "performance"]
    }
]
JSON;
    }

    /**
     * @return string
     */
    public static function getSampleHtmlContent(): string
    {
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>Square Developer</title>
</head>
<body>
    <div id="content">
        <h1>Web Payments SDK Changelog</h1>
    </div>
    <script id="__NEXT_DATA__" type="application/json">
    {
        "props": {
            "pageProps": {
                "data": {
                    "doc": {
                        "pageInView": {
                            "page": {
                                "changelogHistory": [
                                    {
                                        "id": "changelog-1",
                                        "slug": "changelog/webpaymentsdk/2023-05-01",
                                        "linkBack": "https://developer.squareup.com/docs/changelog/webpaymentsdk/2023-05-01",
                                        "changelogType": "Web Payments SDK",
                                        "changelogDate": "2023-05-01",
                                        "summary": "Web Payments SDK 1.0.0 released",
                                        "details": "Initial release of the Web Payments SDK with support for credit card payments.",
                                        "tags": ["web", "payments", "sdk", "release"]
                                    },
                                    {
                                        "id": "changelog-2",
                                        "slug": "changelog/webpaymentsdk/2023-06-15",
                                        "linkBack": "https://developer.squareup.com/docs/changelog/webpaymentsdk/2023-06-15",
                                        "changelogType": "Web Payments SDK",
                                        "changelogDate": "2023-06-15",
                                        "summary": "Web Payments SDK 1.1.0 released",
                                        "details": "Added support for Apple Pay and Google Pay. Fixed various bugs.",
                                        "tags": ["web", "payments", "sdk", "update"]
                                    },
                                    {
                                        "id": "changelog-3",
                                        "slug": "changelog/webpaymentsdk/2023-07-30",
                                        "linkBack": "https://developer.squareup.com/docs/changelog/webpaymentsdk/2023-07-30",
                                        "changelogType": "Web Payments SDK",
                                        "changelogDate": "2023-07-30",
                                        "summary": "Web Payments SDK 1.2.0 released",
                                        "details": "This release includes performance improvements and adds support for new payment methods. The SDK now loads faster and has a smaller footprint. We've also improved error handling and added better documentation.",
                                        "tags": ["web", "payments", "sdk", "performance"]
                                    }
                                ]
                            }
                        }
                    }
                }
            }
        }
    }
    </script>
</body>
</html>
HTML;
    }
}
