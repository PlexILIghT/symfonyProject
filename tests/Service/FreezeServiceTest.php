<?php

namespace App\Tests\Service;

use App\Entity\Application;
use App\Entity\Depositary;
use App\Entity\Portfolio;
use App\Entity\Stock;
use App\Enums\ActionEnum;
use App\Service\FreezeService;
use PHPUnit\Framework\TestCase;

class FreezeServiceTest extends TestCase
{
    private FreezeService $freezeService;

    protected function setUp(): void
    {
        $this->freezeService = new FreezeService();
    }

    public function testFreezeSellApplication(): void
    {
        $stock = $this->createMock(Stock::class);
        $portfolio = $this->createMock(Portfolio::class);
        $depositary = $this->createMock(Depositary::class);

        $application = $this->createMock(Application::class);
        $application->method('getAction')->willReturn(ActionEnum::SELL);
        $application->method('getStock')->willReturn($stock);
        $application->method('getPortfolio')->willReturn($portfolio);
        $application->method('getQuantity')->willReturn(10);

        $portfolio->expects($this->once())
            ->method('getDepositaryByStock')
            ->with($stock)
            ->willReturn($depositary);

        $depositary->expects($this->once())
            ->method('addFreezeQuantity')
            ->with(10);

        $this->freezeService->freezeByApplication($application);

        // +freezeQuantity 10
    }

    public function testFreezeBuyApplication(): void
    {
        $portfolio = $this->createMock(Portfolio::class);

        $application = $this->createMock(Application::class);
        $application->method('getAction')->willReturn(ActionEnum::BUY);
        $application->method('getPortfolio')->willReturn($portfolio);
        $application->method('getTotal')->willReturn(1000.0);

        $portfolio->expects($this->once())
            ->method('addFreezeBalance')
            ->with(1000.0);

        $this->freezeService->freezeByApplication($application);
    }

    public function testUpdateFreezeSellApplication(): void
    {
        $stock = $this->createMock(Stock::class);
        $stock->method('getId')->willReturn(1);

        $portfolio = $this->createMock(Portfolio::class);
        $dep = $this->createMock(Depositary::class);

        $app = $this->createMock(Application::class);
        $app->method('getAction')->willReturn(ActionEnum::SELL);
        $app->method('getPortfolio')->willReturn($portfolio);
        $app->method('getQuantity')->willReturn(15);
        $app->method('getStock')->willReturn($stock);

        $portfolio->method('getDepositaryByStock')
            ->with($stock)
            ->willReturn($dep);

        $dep->expects($this->once())
            ->method('subFreezeQuantity')
            ->withConsecutive([10], [])
            ->willReturnSelf();

        $dep->expects($this->once())
            ->method('addFreezeQuantity')
            ->with(15);

        $this->freezeService->updateFreezeByApplication($app, 10, 100.0);
    }

    public function testUpdateFreezeBuyApplication(): void
    {
        $portfolio = $this->createMock(Portfolio::class);

        $stock = $this->createMock(Stock::class);
        $stock->method('getId')->willReturn(1);

        $application = $this->createMock(Application::class);
        $application->method('getStock')->willReturn($stock);
        $application->method('getAction')->willReturn(ActionEnum::BUY);
        $application->method('getPortfolio')->willReturn($portfolio);
        $application->method('getTotal')->willReturn(1500.0);


        // Я ПОТРАТИЛ 30 МИНУТ ЧТОБЫ ПОНЯТЬ ЧТО ОН ДОЛЖЕН ВЕЕРНУТЬ СЕБЯ В ТЕСТЕ
        $portfolio->expects($this->once())
            ->method('subFreezeBalance')
            ->with(10 * 100.0)->willReturnSelf(); // oldQuantity * oldPrice

        $portfolio->expects($this->once())
            ->method('addFreezeBalance')
            ->with(1500.0);

        $this->freezeService->updateFreezeByApplication($application, 10, 100.0);
    }

    public function testUnfreezeSellApplication(): void
    {
        $stock = $this->createMock(Stock::class);
        $portfolio = $this->createMock(Portfolio::class);
        $depositary = $this->createMock(Depositary::class);

        $application = $this->createMock(Application::class);
        $application->method('getAction')->willReturn(ActionEnum::SELL);
        $application->method('getStock')->willReturn($stock);
        $application->method('getPortfolio')->willReturn($portfolio);
        $application->method('getQuantity')->willReturn(10);

        $portfolio->method('getDepositaryByStock')
            ->with($stock)
            ->willReturn($depositary);

        $depositary->expects($this->once())
            ->method('subFreezeQuantity')
            ->with(10);

        $this->freezeService->unfreezeByApplication($application);
    }

    public function testUnfreezeBuyApplication(): void
    {
        $portfolio = $this->createMock(Portfolio::class);

        $application = $this->createMock(Application::class);
        $application->method('getAction')->willReturn(ActionEnum::BUY);
        $application->method('getPortfolio')->willReturn($portfolio);
        $application->method('getTotal')->willReturn(1000.0);

        $portfolio->expects($this->once())
            ->method('subFreezeBalance')
            ->with(1000.0);

        $this->freezeService->unfreezeByApplication($application);
    }
}