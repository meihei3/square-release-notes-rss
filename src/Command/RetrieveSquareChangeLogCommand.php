<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(name: 'retrieve-square-change-log', description: 'Retrieve Square change log')]
final class RetrieveSquareChangeLogCommand extends Command
{
    public function __construct(
        readonly private HttpClientInterface $httpClient,
        readonly private string              $changelogBaseUrl,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        $response = $this->httpClient->request('GET', $this->changelogBaseUrl . '/connect');

        $output->writeln($response->getContent());

        return Command::SUCCESS;
    }
}
