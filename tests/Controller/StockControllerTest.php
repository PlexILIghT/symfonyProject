<?php

namespace App\Tests\Controller;

use App\Tests\Fixture\PortfolioFixture;
use App\Tests\Fixture\UserFixture;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StockControllerTest extends WebTestCase
{
    private PortfolioFixture $portfolioFixture;
    private KernelBrowser $client;
    private ORMExecutor $executor;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        /**
         * @var EntityManagerInterface $em
         */
        $em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        /**
         * @var User $userAdmin
         */
        $userAdmin->
        $loader = new Loader();
        $loader->addFixture(new UserFixture());
        $loader->addFixture(new PortfolioFixture());

        $this->executor = new ORMExecutor($em, new ORMPurger());
        $this->executor->execute($loader->getFixtures());

        $this->client->loginUser($userAdmin);

    }

    protected function tearDown(): void
    {
        $this->executor->purge();
        parent::tearDown();
    }

    public function testNewStock(): void
    {
        $crawler = $this->client->request('GET', '/stock/new');

        $crawler = $this->client->submitForm('', [
            'stock[name]' => 'Admin Stock',
            'ticker[name]' => 'AST'
        ]);

        $this->client->followRedirect();

        $this->assertResponseIsSuccessful();

    }
}
