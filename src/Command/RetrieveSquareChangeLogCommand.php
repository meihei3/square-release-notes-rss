<?php

namespace App\Command;

use App\Lib\SquareReleaseNotesFetchClientInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'retrieve-square-change-log', description: 'Retrieve Square change log')]
final class RetrieveSquareChangeLogCommand extends Command
{
    public function __construct(
        readonly private SquareReleaseNotesFetchClientInterface $fetchClient,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        $changelogs = $this->fetchClient->fetchSquareAPIsAndSDKsChangeLogList();
        foreach ($changelogs as $changelog) {
            $output->writeln(sprintf(
                'Version: %s, URL: %s, Tags: %s',
                $changelog->version,
                $changelog->url,
                implode(', ', $changelog->tags),
            ));
        }

        return Command::SUCCESS;
    }
}
