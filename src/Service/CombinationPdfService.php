<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Position;
use App\Repository\PositionsRepository;

class CombinationPdfService
{
    public function __construct(
        private PositionsRepository $positionsRepository
    )
    {}

    /**
     * @return Position[]
     */
    public function getCombinationsForLock(int $lock): array
    {
        return $this->positionsRepository->findby([
            'lock' => $lock,
        ]);
    }
}