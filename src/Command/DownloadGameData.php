<?php

declare(strict_types=1);

namespace App\Command;

use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(name: 'app:download-game-data')]
class DownloadGameData extends Command
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly string $apiKey,
        private readonly string $projectRoot,
    ){
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Fetching latest game version');
        $wave1Json = $this->client->request(
            'GET',
            'https://apiv2.legiontd2.com/units/byId/crab_unit_id',
            ['headers' => ['x-api-key' => $this->apiKey]]
        );
        $wave1Data = json_decode($wave1Json->getContent(), true);
        $gameVersion = $wave1Data['version'] ?? throw new Exception('Unable to determine game version');
        $output->writeln("Found latest version '$gameVersion'");

        $output->writeln('Fetching waves data');
        $wavesResponse = $this->client->request(
            'GET',
            'https://apiv2.legiontd2.com/info/waves/0/21',
            ['headers' => ['x-api-key' => $this->apiKey]]
        );
        file_put_contents($this->projectRoot . DIRECTORY_SEPARATOR . 'data/waves.json', $wavesResponse->getContent());
        $output->writeln('Done fetching waves data');

        $output->writeln('Fetching units data');
        $unitsResponse = $this->client->request(
            'GET',
            "https://apiv2.legiontd2.com/units/byVersion/$gameVersion?limit=250&enabled=true",
            ['headers' => ['x-api-key' => $this->apiKey]]
        );
        file_put_contents($this->projectRoot . DIRECTORY_SEPARATOR . 'data/units.json', $unitsResponse->getContent());
        $output->writeln('Done fetching units data');

        return Command::SUCCESS;
    }
}
