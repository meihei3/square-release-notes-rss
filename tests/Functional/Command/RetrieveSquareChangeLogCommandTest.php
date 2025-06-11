<?php

declare(strict_types=1);

namespace App\Tests\Functional\Command;

use App\Command\RetrieveSquareChangeLogCommand;
use App\Lib\ChangelogHistoryFileStoreInterface;
use App\Lib\ChangelogHistoryRSSBuilderInterface;
use App\Lib\SquareReleaseNotesFetchClientInterface;
use App\Tests\Fixtures\ChangelogHistoryFixtures;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class RetrieveSquareChangeLogCommandTest extends TestCase
{
    private SquareReleaseNotesFetchClientInterface $fetchClient;
    private ChangelogHistoryFileStoreInterface $fileStore;
    private ChangelogHistoryRSSBuilderInterface $rssBuilder;
    private CommandTester $commandTester;

    protected function setUp(): void
    {
        // Create mock objects
        $this->fetchClient = $this->createMock(SquareReleaseNotesFetchClientInterface::class);
        $this->fileStore = $this->createMock(ChangelogHistoryFileStoreInterface::class);
        $this->rssBuilder = $this->createMock(ChangelogHistoryRSSBuilderInterface::class);

        // Create the command
        $command = new RetrieveSquareChangeLogCommand(
            $this->fetchClient,
            $this->fileStore,
            $this->rssBuilder
        );

        // Set up the command tester
        $application = new Application();
        $application->add($command);
        $this->commandTester = new CommandTester($command);
    }

    public function testExecuteWithNewChangelogs(): void
    {
        $oldChangelogHistories = [
            ChangelogHistoryFixtures::createSampleChangelogHistories()[0],
            ChangelogHistoryFixtures::createSampleChangelogHistories()[1],
        ];

        $newChangelogHistories = ChangelogHistoryFixtures::createSampleChangelogHistories();

        // Configure the fetch client mock to return new changelogs
        $this->fetchClient->method('fetchSquareAPIsAndSDKsChangelogHistoryList')
            ->willReturn($newChangelogHistories);
        $this->fetchClient->method('fetchMobileSDKsChangelogHistoryList')
            ->willReturn($newChangelogHistories);
        $this->fetchClient->method('fetchWebPaymentsSDKChangelogHistoryList')
            ->willReturn($newChangelogHistories);
        $this->fetchClient->method('fetchPaymentFormChangelogHistoryList')
            ->willReturn($newChangelogHistories);
        $this->fetchClient->method('fetchRequirementsChangelogHistoryList')
            ->willReturn($newChangelogHistories);

        // Configure the file store mock to return old changelogs
        $this->fileStore->method('loadSquareAPIsAndSDKs')
            ->willReturn($oldChangelogHistories);
        $this->fileStore->method('loadMobileSDKs')
            ->willReturn($oldChangelogHistories);
        $this->fileStore->method('loadWebPaymentsSDK')
            ->willReturn($oldChangelogHistories);
        $this->fileStore->method('loadPaymentForm')
            ->willReturn($oldChangelogHistories);
        $this->fileStore->method('loadRequirements')
            ->willReturn($oldChangelogHistories);

        // Expect the file store to be called to store the new changelogs
        $this->fileStore->expects($this->once())
            ->method('storeSquareAPIsAndSDKs')
            ->with($newChangelogHistories);
        $this->fileStore->expects($this->once())
            ->method('storeMobileSDKs')
            ->with($newChangelogHistories);
        $this->fileStore->expects($this->once())
            ->method('storeWebPaymentsSDK')
            ->with($newChangelogHistories);
        $this->fileStore->expects($this->once())
            ->method('storePaymentForm')
            ->with($newChangelogHistories);
        $this->fileStore->expects($this->once())
            ->method('storeRequirements')
            ->with($newChangelogHistories);

        // Expect the RSS builder to be called to build the new RSS feeds
        $this->rssBuilder->expects($this->once())
            ->method('buildSquareAPIsAndSDKs')
            ->with($newChangelogHistories);
        $this->rssBuilder->expects($this->once())
            ->method('buildMobileSDKs')
            ->with($newChangelogHistories);
        $this->rssBuilder->expects($this->once())
            ->method('buildWebPaymentsSDK')
            ->with($newChangelogHistories);
        $this->rssBuilder->expects($this->once())
            ->method('buildPaymentForm')
            ->with($newChangelogHistories);
        $this->rssBuilder->expects($this->once())
            ->method('buildRequirements')
            ->with($newChangelogHistories);

        // Execute the command
        $this->commandTester->execute([]);

        // Verify the command executed successfully
        $this->assertEquals(0, $this->commandTester->getStatusCode());
    }

    public function testExecuteWithNoNewChangelogs(): void
    {
        $existingChangelogHistories = ChangelogHistoryFixtures::createSampleChangelogHistories();

        // Configure the fetch client mock to return the same changelogs
        $this->fetchClient->method('fetchSquareAPIsAndSDKsChangelogHistoryList')
            ->willReturn($existingChangelogHistories);
        $this->fetchClient->method('fetchMobileSDKsChangelogHistoryList')
            ->willReturn($existingChangelogHistories);
        $this->fetchClient->method('fetchWebPaymentsSDKChangelogHistoryList')
            ->willReturn($existingChangelogHistories);
        $this->fetchClient->method('fetchPaymentFormChangelogHistoryList')
            ->willReturn($existingChangelogHistories);
        $this->fetchClient->method('fetchRequirementsChangelogHistoryList')
            ->willReturn($existingChangelogHistories);

        // Configure the file store mock to return the same changelogs
        $this->fileStore->method('loadSquareAPIsAndSDKs')
            ->willReturn($existingChangelogHistories);
        $this->fileStore->method('loadMobileSDKs')
            ->willReturn($existingChangelogHistories);
        $this->fileStore->method('loadWebPaymentsSDK')
            ->willReturn($existingChangelogHistories);
        $this->fileStore->method('loadPaymentForm')
            ->willReturn($existingChangelogHistories);
        $this->fileStore->method('loadRequirements')
            ->willReturn($existingChangelogHistories);

        // Expect the file store and RSS builder NOT to be called
        $this->fileStore->expects($this->never())->method('storeSquareAPIsAndSDKs');
        $this->fileStore->expects($this->never())->method('storeMobileSDKs');
        $this->fileStore->expects($this->never())->method('storeWebPaymentsSDK');
        $this->fileStore->expects($this->never())->method('storePaymentForm');
        $this->fileStore->expects($this->never())->method('storeRequirements');

        $this->rssBuilder->expects($this->never())->method('buildSquareAPIsAndSDKs');
        $this->rssBuilder->expects($this->never())->method('buildMobileSDKs');
        $this->rssBuilder->expects($this->never())->method('buildWebPaymentsSDK');
        $this->rssBuilder->expects($this->never())->method('buildPaymentForm');
        $this->rssBuilder->expects($this->never())->method('buildRequirements');

        // Execute the command
        $this->commandTester->execute([]);

        // Verify the command executed successfully
        $this->assertEquals(0, $this->commandTester->getStatusCode());
    }
}
