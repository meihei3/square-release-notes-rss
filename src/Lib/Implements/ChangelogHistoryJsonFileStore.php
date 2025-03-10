<?php

declare(strict_types=1);

namespace App\Lib\Implements;

use App\Lib\ChangelogHistory;
use App\Lib\ChangelogHistoryFileStoreInterface;
use RuntimeException;
use Symfony\Component\Serializer\SerializerInterface;

use function file_get_contents;
use function file_put_contents;

final readonly class ChangelogHistoryJsonFileStore implements ChangelogHistoryFileStoreInterface
{
    private const string CONNECT_JSON_FILE = '/json/connect.json';
    private const string MOBILE_SDKS_JSON_FILE = '/json/mobile-sdks.json';

    public function __construct(
        private string $publicDirectory,
        private SerializerInterface $serializer,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function storeSquareAPIsAndSDKs(array $changelogHistories): void
    {
        $json = $this->serializer->serialize($changelogHistories, 'json', ['json_encode_options' => JSON_PRETTY_PRINT]);

        $success = file_put_contents($this->publicDirectory . self::CONNECT_JSON_FILE, $json);
        if ($success === false) {
            throw new RuntimeException('Failed to write JSON to file');
        }
    }

    /**
     * @inheritDoc
     */
    public function loadSquareAPIsAndSDKs(): array
    {
        $json = file_get_contents($this->publicDirectory . self::CONNECT_JSON_FILE);
        if ($json === false) {
            throw new RuntimeException('Failed to read JSON from file');
        }

        return $this->serializer->deserialize($json, ChangelogHistory::class . '[]', 'json');
    }

    /**
     * @inheritDoc
     */
    public function storeMobileSDKs(array $changelogHistories): void
    {
        $json = $this->serializer->serialize($changelogHistories, 'json', ['json_encode_options' => JSON_PRETTY_PRINT]);

        $success = file_put_contents($this->publicDirectory . self::MOBILE_SDKS_JSON_FILE, $json);
        if ($success === false) {
            throw new RuntimeException('Failed to write JSON to file');
        }
    }

    /**
     * @inheritDoc
     */
    public function loadMobileSDKs(): array
    {
        $json = file_get_contents($this->publicDirectory . self::MOBILE_SDKS_JSON_FILE);
        if ($json === false) {
            throw new RuntimeException('Failed to read JSON from file');
        }

        return $this->serializer->deserialize($json, ChangelogHistory::class . '[]', 'json');
    }
}
