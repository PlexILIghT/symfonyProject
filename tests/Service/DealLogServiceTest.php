<?php


namespace App\Tests\Service;

use App\Entity\Application;
use App\Entity\DealLog;
use App\Entity\Depositary;
use App\Entity\Portfolio;
use App\Entity\Stock;
use App\Enums\ActionEnum;
use App\Repository\DealLogRepository;
use App\Service\DealLogService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Doctrine\Common\Collections\ArrayCollection;

class DealLogServiceTest extends TestCase
{
    private DealLogRepository|MockObject $dealLogRepositoryMock;
    private DealLogService $dealLogService;

    protected function setUp(): void
    {
        $this->dealLogRepositoryMock = $this->createMock(DealLogRepository::class);
        $this->dealLogService = new DealLogService($this->dealLogRepositoryMock);
    }

    public function testRegisterDealLogWithBuyAndSell(): void
    {
        $stock = $this->createMock(Stock::class);

        $buyApp = $this->createMock(Application::class);
        $buyApp->method('getAction')->willReturn(ActionEnum::BUY);
        $buyApp->method('getStock')->willReturn($stock);
        $buyApp->method('getPrice')->willReturn(100.0);
        $buyApp->method('getQuantity')->willReturn(10);

        $sellApp = $this->createMock(Application::class);
        $sellApp->method('getAction')->willReturn(ActionEnum::SELL);

        $portfolioBuy = $this->createMock(Portfolio::class);
        $portfolioSell = $this->createMock(Portfolio::class);

        $buyApp->method('getPortfolio')->willReturn($portfolioBuy);
        $sellApp->method('getPortfolio')->willReturn($portfolioSell);

        $this->dealLogRepositoryMock
            ->expects($this->once())
            ->method('saveDealLog');

        $dealLog = $this->dealLogService->registerDealLog($buyApp, $sellApp);

        $this->assertSame($stock, $dealLog->getStock());
        $this->assertEquals(100.0, $dealLog->getPrice());
        $this->assertSame($portfolioBuy, $dealLog->getBuyPortfolio());
        $this->assertSame($portfolioSell, $dealLog->getSellPortfolio());
        $this->assertEquals(10, $dealLog->getQuantity());
    }

    public function testRegisterDealLogWithReversedArguments(): void
    {
        $stock = $this->createMock(Stock::class);

        $appSell = $this->createMock(Application::class);
        $appSell->method('getAction')->willReturn(ActionEnum::SELL);
        $appSell->method('getStock')->willReturn($stock);
        $appSell->method('getPrice')->willReturn(90.0);
        $appSell->method('getQuantity')->willReturn(5);

        $appBuy = $this->createMock(Application::class);
        $appBuy->method('getAction')->willReturn(ActionEnum::BUY);
        $appBuy->method('getStock')->willReturn($stock);
        $appBuy->method('getPrice')->willReturn(90.0);
        $appBuy->method('getQuantity')->willReturn(5);

        $buyPorfolio = $this->createMock(Portfolio::class);
        $sellPortfolio = $this->createMock(Portfolio::class);

        $appBuy->method('getPortfolio')->willReturn($buyPorfolio);
        $appSell->method('getPortfolio')->willReturn($sellPortfolio);

        $this->dealLogRepositoryMock
            ->expects($this->once())
            ->method('saveDealLog');

        $dealLog = $this->dealLogService->registerDealLog($appSell, $appBuy);

        $this->assertSame($stock, $dealLog->getStock());
        $this->assertEquals(90.0, $dealLog->getPrice());
        $this->assertSame($buyPorfolio, $dealLog->getBuyPortfolio());
        $this->assertSame($sellPortfolio, $dealLog->getSellPortfolio());
        $this->assertEquals(5, $dealLog->getQuantity());
    }

    public function testCalculateDeltaWithTransactions(): void
    {
        $stock = $this->createMock(Stock::class);
        $stock->method('getId')->willReturn(1);

        $portfolio = $this->createMock(Portfolio::class);

        $depositary = $this->createMock(Depositary::class);
        $depositary->method('getPortfolio')->willReturn($portfolio);
        $depositary->method('getStock')->willReturn($stock);

        $buyDealLog1 = $this->createMock(DealLog::class);
        $buyDealLog1->method('getStock')->willReturn($stock);
        $buyDealLog1->method('getQuantity')->willReturn(5);
        $buyDealLog1->method('getPrice')->willReturn(100.0);

        $buyDealLog2 = $this->createMock(DealLog::class);
        $buyDealLog2->method('getStock')->willReturn($stock);
        $buyDealLog2->method('getQuantity')->willReturn(3);
        $buyDealLog2->method('getPrice')->willReturn(110.0);

        $sellDealLog = $this->createMock(DealLog::class);
        $sellDealLog->method('getStock')->willReturn($stock);
        $sellDealLog->method('getQuantity')->willReturn(4);
        $sellDealLog->method('getPrice')->willReturn(120.0);

        $buyDealLogs = new ArrayCollection([$buyDealLog1, $buyDealLog2]);
        $sellDealLogs = new ArrayCollection([$sellDealLog]);

        $portfolio->method('getBuyDealLogs')->willReturn($buyDealLogs);
        $portfolio->method('getSellDealLogs')->willReturn($sellDealLogs);

        $latestDealLog = $this->createMock(DealLog::class);
        $latestDealLog->method('getPrice')->willReturn(150.0);

        $this->dealLogRepositoryMock
            ->method('findLatestByStock')
            ->with($stock)
            ->willReturn($latestDealLog);

        // Расчет:
        // investedSum = (5*100 + 3*110) - (4*120) = 830 - 480 = 350
        // actualQuantity = (5+3) - 4 = 4
        // actualSum = 4 * 150 = 600
        // delta = 600 - 350 = 250
        $delta = $this->dealLogService->calculateDelta($depositary);

        $this->assertEquals(250.0, $delta);
    }

    public function testCalculateDeltaWithNoLatestDealLog(): void
    {
        $stock = $this->createMock(Stock::class);
        $portfolio = $this->createMock(Portfolio::class);

        $depositary = $this->createMock(Depositary::class);
        $depositary->method('getPortfolio')->willReturn($portfolio);
        $depositary->method('getStock')->willReturn($stock);

        $portfolio->method('getBuyDealLogs')->willReturn(new ArrayCollection());
        $portfolio->method('getSellDealLogs')->willReturn(new ArrayCollection());

        $this->dealLogRepositoryMock
            ->method('findLatestByStock')
            ->with($stock)
            ->willReturn(null);

        $delta = $this->dealLogService->calculateDelta($depositary);
        $this->assertEquals(0.0, $delta);
    }
}