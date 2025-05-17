<?php

namespace App\Tests\Fixture;

use App\Entity\Stock;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class StockFixture extends AbstractFixture
{
    public const TEST_STOCK_REFERENCE = 'test-stock';
    public const ANOTHER_STOCK_REFERENCE = 'another-stock';
    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager): void
    {
        $testStock = new Stock();
        $testStock->setName('Test Stock');
        $testStock->setTicker('TSTCK');

        $manager->persist($testStock);

        $this->addReference(self::TEST_STOCK_REFERENCE, $testStock);

        $anotherStock = new Stock();
        $anotherStock->setName('Another Test Stock');
        $anotherStock->setTicker('ATS');

        $manager->persist($anotherStock);

        $this->addReference(self::ANOTHER_STOCK_REFERENCE, $anotherStock);
        $manager->flush();
    }
}