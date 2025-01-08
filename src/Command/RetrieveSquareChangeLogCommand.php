<?php

namespace App\Command;

use App\Lib\ChangelogFileStoreInterface;
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
        readonly private ChangelogFileStoreInterface            $fileStore,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        $changelogs = $this->fetchClient->fetchSquareAPIsAndSDKsChangelogHistoryList();
        $previous = $this->fileStore->loadSquareAPIsAndSDKs();

        // Compare the changelogs and store the new ones
        $diff = array_filter($changelogs, fn($changelog) => !in_array($changelog, $previous));
        if (count($diff) > 0) {
            // change log を上書き保存
            $this->fileStore->storeSquareAPIsAndSDKs($changelogs);
        }

        return Command::SUCCESS;
    }
}
