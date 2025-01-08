<?php
declare(strict_types=1);

namespace App\Lib;

use RuntimeException;
use Symfony\Component\Serializer\SerializerInterface;
use function file_get_contents;
use function file_put_contents;

final readonly class ChangelogJsonFileStore implements ChangelogFileStoreInterface
{
    private const string CONNECT_JSON_FILE = '/json/connect.json';

    public function __construct(
        private string              $publicDirectory,
        private SerializerInterface $serializer,
    ) {}

    /**
     * @inheritDoc
     */
    public function storeSquareAPIsAndSDKs(array $changelogs): void {
        $json = $this->serializer->serialize($changelogs, 'json', ['json_encode_options' => JSON_PRETTY_PRINT]);

        $success = file_put_contents($this->publicDirectory . self::CONNECT_JSON_FILE, $json);
        if ($success === false) {
            throw new RuntimeException('Failed to write JSON to file');
        }
    }

    /**
     * @inheritDoc
     */
    public function loadSquareAPIsAndSDKs(): array {
        $json = file_get_contents($this->publicDirectory . self::CONNECT_JSON_FILE);
        if ($json === false) {
            throw new RuntimeException('Failed to read JSON from file');
        }

        return $this->serializer->deserialize($json, ChangelogHistory::class . '[]', 'json');
    }
}
