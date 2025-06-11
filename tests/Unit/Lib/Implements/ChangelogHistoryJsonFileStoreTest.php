<?php

declare(strict_types=1);

namespace App\Tests\Unit\Lib\Implements;

use App\Lib\ChangelogHistory;
use App\Lib\Implements\ChangelogHistoryJsonFileStore;
use App\Tests\Fixtures\ChangelogHistoryFixtures;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\Serializer\SerializerInterface;

class ChangelogHistoryJsonFileStoreTest extends TestCase
{
    private string $tempDir;
    private SerializerInterface $serializer;
    private ChangelogHistoryJsonFileStore $fileStore;

    protected function setUp(): void
    {
        // Create a temporary directory for test files
        $this->tempDir = sys_get_temp_dir() . '/square-release-notes-test-' . uniqid();
        mkdir($this->tempDir . '/json', 0777, true);

        // Create a mock serializer
        $this->serializer = $this->createMock(SerializerInterface::class);

        // Create the file store with the temp directory
        $this->fileStore = new ChangelogHistoryJsonFileStore(
            $this->tempDir,
            $this->serializer
        );
    }

    protected function tearDown(): void
    {
        // Clean up temporary files
        if (file_exists($this->tempDir . '/json/webpaymentsdk.json')) {
            unlink($this->tempDir . '/json/webpaymentsdk.json');
        }
        if (file_exists($this->tempDir . '/json/connect.json')) {
            unlink($this->tempDir . '/json/connect.json');
        }
        if (file_exists($this->tempDir . '/json/mobile-sdks.json')) {
            unlink($this->tempDir . '/json/mobile-sdks.json');
        }
        if (file_exists($this->tempDir . '/json/paymentform.json')) {
            unlink($this->tempDir . '/json/paymentform.json');
        }
        if (file_exists($this->tempDir . '/json/requirements.json')) {
            unlink($this->tempDir . '/json/requirements.json');
        }

        // Remove the temp directory
        rmdir($this->tempDir . '/json');
        rmdir($this->tempDir);
    }

    public function testStoreAndLoadWebPaymentsSDK(): void
    {
        $changelogHistories = ChangelogHistoryFixtures::createSampleChangelogHistories();
        $jsonContent = ChangelogHistoryFixtures::getSampleJsonContent();

        // Configure the serializer mock
        $this->serializer->expects($this->once())
            ->method('serialize')
            ->with($changelogHistories, 'json', ['json_encode_options' => JSON_PRETTY_PRINT])
            ->willReturn($jsonContent);

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with($jsonContent, ChangelogHistory::class . '[]', 'json')
            ->willReturn($changelogHistories);

        // Store the changelog histories
        $this->fileStore->storeWebPaymentsSDK($changelogHistories);

        // Verify the file was created
        $this->assertFileExists($this->tempDir . '/json/webpaymentsdk.json');

        // Load the changelog histories
        $loadedHistories = $this->fileStore->loadWebPaymentsSDK();

        // Verify the loaded histories match the original
        $this->assertSame($changelogHistories, $loadedHistories);
    }

    public function testStoreAndLoadSquareAPIsAndSDKs(): void
    {
        $changelogHistories = ChangelogHistoryFixtures::createSampleChangelogHistories();
        $jsonContent = ChangelogHistoryFixtures::getSampleJsonContent();

        // Configure the serializer mock
        $this->serializer->expects($this->once())
            ->method('serialize')
            ->with($changelogHistories, 'json', ['json_encode_options' => JSON_PRETTY_PRINT])
            ->willReturn($jsonContent);

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with($jsonContent, ChangelogHistory::class . '[]', 'json')
            ->willReturn($changelogHistories);

        // Store the changelog histories
        $this->fileStore->storeSquareAPIsAndSDKs($changelogHistories);

        // Verify the file was created
        $this->assertFileExists($this->tempDir . '/json/connect.json');

        // Load the changelog histories
        $loadedHistories = $this->fileStore->loadSquareAPIsAndSDKs();

        // Verify the loaded histories match the original
        $this->assertSame($changelogHistories, $loadedHistories);
    }

    public function testLoadThrowsExceptionWhenFileDoesNotExist(): void
    {
        // Expect an exception when trying to load a non-existent file
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Failed to read JSON from file');

        $this->fileStore->loadWebPaymentsSDK();
    }

    public function testStoreThrowsExceptionWhenDirectoryIsNotWritable(): void
    {
        // Create a temp directory with no write permissions
        $readOnlyDir = $this->tempDir . '/readonly';
        mkdir($readOnlyDir, 0400, true);

        $fileStore = new ChangelogHistoryJsonFileStore(
            $readOnlyDir,
            $this->serializer
        );

        $changelogHistories = ChangelogHistoryFixtures::createSampleChangelogHistories();
        $jsonContent = ChangelogHistoryFixtures::getSampleJsonContent();

        // Configure the serializer mock
        $this->serializer->expects($this->once())
            ->method('serialize')
            ->willReturn($jsonContent);

        // Expect an exception when trying to write to a non-writable directory
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Failed to write JSON to file');

        $fileStore->storeWebPaymentsSDK($changelogHistories);

        // Clean up
        rmdir($readOnlyDir);
    }
}
