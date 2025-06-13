<?php

declare(strict_types=1);

namespace App\Command;

use App\Lib\ChangelogHistoryFileStoreInterface;
use App\Lib\ChangelogHistoryRSSBuilderInterface;
use App\Lib\SquareReleaseNotesFetchClientInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function array_filter;
use function count;
use function in_array;

#[AsCommand(name: 'retrieve-square-change-log', description: 'Retrieve Square change log')]
final class RetrieveSquareChangeLogCommand extends Command
{
    public function __construct(
        readonly private SquareReleaseNotesFetchClientInterface $fetchClient,
        readonly private ChangelogHistoryFileStoreInterface $fileStore,
        readonly private ChangelogHistoryRSSBuilderInterface $rssBuilder,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $changelogs = $this->fetchClient->fetchSquareAPIsAndSDKsChangelogHistoryList();
        $previous = $this->fileStore->loadSquareAPIsAndSDKs();

        // Compare the changelogs and store the new ones
        $diff = array_filter($changelogs, fn($changelog) => !in_array($changelog, $previous));
        if (count($diff) > 0) {
            // change log を上書き保存
            $this->fileStore->storeSquareAPIsAndSDKs($changelogs);
            $this->rssBuilder->buildSquareAPIsAndSDKs($changelogs);
        }

        $changelogs = $this->fetchClient->fetchMobileSDKsChangelogHistoryList();
        $previous = $this->fileStore->loadMobileSDKs();

        // Compare the changelogs and store the new ones
        $diff = array_filter($changelogs, fn($changelog) => !in_array($changelog, $previous));
        if (count($diff) > 0) {
            // change log を上書き保存
            $this->fileStore->storeMobileSDKs($changelogs);
            $this->rssBuilder->buildMobileSDKs($changelogs);
        }

        $changelogs = $this->fetchClient->fetchWebPaymentsSDKChangelogHistoryList();
        $previous = $this->fileStore->loadWebPaymentsSDK();

        // Compare the changelogs and store the new ones
        $diff = array_filter($changelogs, fn($changelog) => !in_array($changelog, $previous));
        if (count($diff) > 0) {
            // change log を上書き保存
            $this->fileStore->storeWebPaymentsSDK($changelogs);
            $this->rssBuilder->buildWebPaymentsSDK($changelogs);
        }

        $changelogs = $this->fetchClient->fetchPaymentFormChangelogHistoryList();
        $previous = $this->fileStore->loadPaymentForm();

        // Compare the changelogs and store the new ones
        $diff = array_filter($changelogs, fn($changelog) => !in_array($changelog, $previous));
        if (count($diff) > 0) {
            // change log を上書き保存
            $this->fileStore->storePaymentForm($changelogs);
            $this->rssBuilder->buildPaymentForm($changelogs);
        }

        $changelogs = $this->fetchClient->fetchRequirementsChangelogHistoryList();
        $previous = $this->fileStore->loadRequirements();

        // Compare the changelogs and store the new ones
        $diff = array_filter($changelogs, fn($changelog) => !in_array($changelog, $previous));
        if (count($diff) > 0) {
            // change log を上書き保存
            $this->fileStore->storeRequirements($changelogs);
            $this->rssBuilder->buildRequirements($changelogs);
        }

        return Command::SUCCESS;
    }
}
