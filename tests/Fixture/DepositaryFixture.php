<?php

namespace App\Tests\Fixture;

use App\Entity\Depositary;
use App\Entity\Portfolio;
use App\Entity\Stock;
use App\Tests\Fixture\PortfolioFixture;
use App\Tests\Fixture\StockFixture;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DepositaryFixture extends AbstractFixture implements DependentFixtureInterface
{
    public const DEPOSITARY_ADMIN_REFERENCE = 'depositary-admin';
    public const DEPOSITARY_USER_REFERENCE = 'depositary-user';

    public function load(ObjectManager $manager): void
    {
        // Depositary для админского портфеля
        $adminDepositary = new Depositary();
        $adminDepositary->setStock($this->getReference(StockFixture::STOCK_TEST_REFERENCE, Stock::class));
        $adminDepositary->setPortfolio($this->getReference(PortfolioFixture::PORTFOLIO_ADMIN_REFERENCE, Portfolio::class));
        $adminDepositary->setQuantity(100);
        $adminDepositary->setFreezeQuantity(10);

        $manager->persist($adminDepositary);
        $this->addReference(self::DEPOSITARY_ADMIN_REFERENCE, $adminDepositary);

        // Depositary для пользовательского портфеля
        $userDepositary = new Depositary();
        $userDepositary->setStock($this->getReference(StockFixture::STOCK_ANOTHER_REFERENCE, Stock::class));
        $userDepositary->setPortfolio($this->getReference(PortfolioFixture::PORTFOLIO_USER_REFERENCE, Portfolio::class));
        $userDepositary->setQuantity(50);
        $userDepositary->setFreezeQuantity(5);

        $manager->persist($userDepositary);
        $this->addReference(self::DEPOSITARY_USER_REFERENCE, $userDepositary);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            StockFixture::class,
            PortfolioFixture::class,
        ];
    }
}