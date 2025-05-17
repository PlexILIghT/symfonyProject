<?php

namespace App\Tests\Repository;

use App\Entity\DealLog;
use App\Entity\Stock;
use App\Repository\DealLogRepository;
use App\Tests\Fixture\ApplicationFixture;
use App\Tests\Fixture\DealLogFixture;
use App\Tests\Fixture\PortfolioFixture;
use App\Tests\Fixture\StockFixture;
use App\Tests\Fixture\UserFixture;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DealLogRepositoryTest extends KernelTestCase
{
    private ApplicationFixture $applicationFixture;
    private PortfolioFixture $portfolioFixture;
    private DealLogFixture $dealLogFixture;
    private StockFixture $stockFixture;
    private DealLogRepository $dealLogRepository;


    public function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->assertSame('test', $kernel->getEnvironment());

        $em = self::getContainer()->get('doctrine')->getManager();
        $this->assertInstanceOf(EntityManager::class, $em);

        $loader = new Loader();
        $loader->addFixture($this->stockFixture = new StockFixture());
        $loader->addFixture($this->portfolioFixture = new PortfolioFixture());
        $loader->addFixture($this->dealLogFixture = new DealLogFixture());

        (new ORMExecutor($em, new ORMPurger()))->execute($loader->getFixtures());

        $this->dealLogRepository = $em->getRepository(DealLog::class);
    }

    public function testFindByStock(): void
    {
        $findableStock = $this->stockFixture->getReference(StockFixture::TEST_STOCK_REFERENCE, Stock::class);
        $dealLogs = $this->dealLogRepository->findByStock($findableStock);

        $this->assertCount(2, $dealLogs);
        foreach ($dealLogs as $dealLog) {
            $this->assertEquals($findableStock, $dealLog->getStock());
        }
    }

    public function testFindLatestByStock(): void
    {
        $findableStock = $this->stockFixture->getReference(StockFixture::TEST_STOCK_REFERENCE, Stock::class);
        $latestDealLog = $this->dealLogRepository->findLatestByStock($findableStock);

        $this->assertEquals(
            $this->dealLogFixture->getReference(DealLogFixture::NEW_DEAL_LOG, DealLog::class),
            $latestDealLog
        );
    }

    public function testNotFoundByStock(): void
    {
        $dealLogs = $this->dealLogRepository->findByStock(
            $this->stockFixture->getReference(StockFixture::ANOTHER_STOCK_REFERENCE, Stock::class),
        );

        $this->assertEmpty($dealLogs);
    }

    public function testLatestByStockNotFound(): void
    {
        $this->assertNull(
            $this->dealLogRepository->findLatestByStock(
                $this->stockFixture->getReference(StockFixture::ANOTHER_STOCK_REFERENCE, Stock::class),
            )
        );
    }

}
