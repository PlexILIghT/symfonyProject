<?php

namespace App\Service;

use App\Entity\Application;
use App\Entity\DealLog;
use App\Entity\Depositary;
use App\Enums\ActionEnum;
use App\Repository\DealLogRepository;
use DateTimeImmutable;

class DealLogService
{
    public function __construct(private readonly DealLogRepository $dealLogRepository)
    {

    }

    public function registerDealLog(Application $buyApplication, Application $sellApplication): void
    {
        $price = $buyApplication->getPrice();
        $stock = $buyApplication->getStock();
        $timestamp = new DateTimeImmutable("now UTC");

        $dealLog = new DealLog();
        $dealLog->setPrice($price);
        $dealLog->setTimestamp($timestamp);
        $dealLog->setStock($stock);
        $dealLog->setBuyPorfolio($buyApplication->getPortfolio());
        $dealLog->setSellPortfolio($sellApplication->getPortfolio());
        $dealLog->setQuantity($buyApplication->getQuantity());

        $this->dealLogRepository->saveDealLog($dealLog);
    }

    public function calculateDelta(Depositary $depositary): float
    {
        $sellDealLogs =
            $depositary->getPortfolio()->getSellDealLogs()->filter(
                function (DealLog $sellDealLog) use ($depositary) {
                    return $depositary->getStock()->getId() === $sellDealLog->getStock()->getId();
            }
            );

        $buyDealLogs =
            $depositary->getPortfolio()->getBuyDealLogs()->filter(
                function (DealLog $buyDealLog) use ($depositary) {
                    return $depositary->getStock()->getId() === $buyDealLog->getStock()->getId();
                }
            );

        $latestDealLog = $this->dealLogRepository->findLatestByStock($depositary->getStock());

        $investedSum = 0.0;
        $actualQuantity = 0;

        foreach ($buyDealLogs as $buyDealLog) {
            $investedSum += $buyDealLog->getQuantity() * $buyDealLog->getPrice();
            $actualQuantity += $buyDealLog->getQuantity();
        }

        foreach ($sellDealLogs as $sellDealLog) {
            $investedSum -= $sellDealLog->getQuantity() * $sellDealLog->getPrice();
            $actualQuantity -= $sellDealLog->getQuantity();
        }

        $actualSum = $actualQuantity * ($latestDealLog?->getPrice() ?? 0.0);

        return $actualSum - $investedSum;
    }
}
