<?php

namespace App\Service;

use App\Entity\Application;
use App\Entity\DealLog;
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

        $this->dealLogRepository->saveDealLog($dealLog);
    }
}
