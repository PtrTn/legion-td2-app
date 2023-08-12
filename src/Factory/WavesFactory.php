<?php

declare(strict_types=1);

namespace App\Factory;

use App\Dto\Wave;
use App\Repository\UnitsRepository;
use Exception;

final class WavesFactory
{
    public function __construct(public readonly UnitsRepository $unitsRepository, private readonly string $projectRoot)
    {
    }

    /** @return Wave[] */
    public function create(): array
    {
        $wavesJson = file_get_contents($this->projectRoot . DIRECTORY_SEPARATOR . 'data/waves.json');
        $wavesData = json_decode($wavesJson, true);
        $waves = [];
        foreach ($wavesData as $waveData) {
            $waves[] = $this->createWave($waveData);
        }

        usort($waves, fn(Wave $waveA, Wave $waveB) => $waveA->waveNumber <=> $waveB->waveNumber);

        return $waves;
    }

    private function createWave(array $waveData): Wave
    {
        if (empty($waveData['_id']) || empty($waveData['waveUnitId']) || empty($waveData['levelNum'])) {
            throw new Exception(sprintf('Missing unit data for "%s"', $waveData['unitId'] ?? 'Unknown'));
        }

        $unit = $this->unitsRepository->getCreatureById($waveData['waveUnitId']);

        return new Wave(
            $waveData['_id'],
            (int) $waveData['levelNum'],
            $unit
        );
    }
}
