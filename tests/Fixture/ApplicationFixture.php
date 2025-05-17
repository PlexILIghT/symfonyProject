<?php

namespace App\Tests\Fixture;

use App\Entity\Application;
use App\Entity\Portfolio;
use App\Entity\Stock;
use App\Entity\User;
use App\Enums\ActionEnum;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ApplicationFixture extends AbstractFixture implements DependentFixtureInterface
{
    public const ADMIN_APPLICATION_REFERENCE = 'admin-application';

    public function load(ObjectManager $manager): void
    {
        $application = new Application();
        $application->setPrice(1);
        $application->setQuantity(1);
        $application->setAction(ActionEnum::SELL);

        $application->setPortfolio($this->getReference(PortfolioFixture::PORTFOLIO_ADMIN_REFERENCE, Portfolio::class));

        $application->setStock($this->getReference(StockFixture::TEST_STOCK_REFERENCE, Stock::class));

        $manager->persist($application);
        $manager->flush();

        $this->addReference(self::ADMIN_APPLICATION_REFERENCE, $application);
    }

    public function getDependencies(): array
    {
        return [PortfolioFixture::class, StockFixture::class];
    }
}