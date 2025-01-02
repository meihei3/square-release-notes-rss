<?php
declare(strict_types=1);

namespace App\Lib;

use JsonException;
use RuntimeException;
use function file_put_contents;
use function json_encode;

final readonly class ChangelogJsonFileStore implements ChangelogFileStoreInterface
{
    public function __construct(
        private string $publicDirectory,
    ) {}

    /**
     * @inheritDoc
     */
    public function storeSquareAPIsAndSDKs(array $changelogs): void {
        try {
            $json = json_encode($changelogs, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
            if ($json === false) {
                throw new RuntimeException('Failed to encode JSON');
            }
        } catch (JsonException $e) {
            throw new RuntimeException('Failed to encode JSON', previous: $e);
        }

        $success = file_put_contents($this->publicDirectory . '/json/connect.json', $json);
        if ($success === false) {
            throw new RuntimeException('Failed to write JSON to file');
        }
    }

    /**
     * @inheritDoc
     */
    public function loadSquareAPIsAndSDKs(): array {
        $json = file_get_contents($this->publicDirectory . '/json/connect.json');
        if ($json === false) {
            throw new RuntimeException('Failed to read JSON from file');
        }

        try {
            $changelogRows = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new RuntimeException('Failed to decode JSON', previous: $e);
        }

        $changelogs = [];
        foreach ($changelogRows as $changelogRow) {
            $changelogs[] = new Changelog(version: $changelogRow['version'], url: $changelogRow['url'], tags: $changelogRow['tags'],);
        }

        return $changelogs;
    }
}
