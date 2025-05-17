<?php

namespace App\Tests\Fixture;

use App\Controller\StockController;
use App\Entity\DealLog;
use App\Entity\Portfolio;
use App\Entity\Stock;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DealLogFixture extends AbstractFixture implements DependentFixtureInterface
{

    public const OLD_DEAL_LOG = 'old-deal-log';
    public const NEW_DEAL_LOG = 'new-deal-log';

    public function load(ObjectManager $manager): void
    {
        $olderDealLog = new DealLog();
        $olderDealLog
            ->setPrice(1)
            ->setQuantity(1)
            ->setTimestamp(new \DateTimeImmutable('2025-01-01 00:00:00'))
            ->setBuyPortfolio(
                $this->getReference(PortfolioFixture::PORTFOLIO_USER_REFERENCE, Portfolio::class)
            )
            ->setSellPortfolio(
                $this->getReference(PortfolioFixture::PORTFOLIO_ADMIN_REFERENCE, Portfolio::class)
            )
            ->setStock(
                $this->getReference(StockFixture::TEST_STOCK_REFERENCE, Stock::class)
            );

        $manager->persist($olderDealLog);


        $this->addReference(self::OLD_DEAL_LOG, $olderDealLog);

        $newDealLog = new DealLog();
        $newDealLog
            ->setPrice(2)
            ->setQuantity(2)
            ->setTimestamp(new \DateTimeImmutable('2025-01-01 00:00:00'))
            ->setBuyPortfolio(
                $this->getReference(PortfolioFixture::PORTFOLIO_ADMIN_REFERENCE, Portfolio::class)
            )
            ->setSellPortfolio(
                $this->getReference(PortfolioFixture::PORTFOLIO_USER_REFERENCE, Portfolio::class)
            )
            ->setStock(
                $this->getReference(StockFixture::TEST_STOCK_REFERENCE, Stock::class)
            );

        $manager->persist($newDealLog);
        $manager->flush();

        $this->addReference(self::NEW_DEAL_LOG, $newDealLog);
    }

    public function getDependencies(): array
    {
        return [StockFixture::class, PortfolioFixture::class];
    }
}