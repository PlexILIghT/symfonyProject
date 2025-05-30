<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\Fixture\PortfolioFixture;
use App\Tests\Fixture\UserFixture;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProfileControllerTest extends WebTestCase
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

        $loader = new Loader();
        $loader->addFixture(new UserFixture());
        $loader->addFixture(new PortfolioFixture());

        $this->executor = new ORMExecutor($em, new ORMPurger());
        $this->executor->execute($loader->getFixtures());


    }

    public function testSomething(): void
    {
        /**
         * @var UserRepository $userRepository
         */
        $userRepository = $this->client->getContainer()->get(UserRepository::class);

        /**
         * @var UserRepository
         */
        $userAdmin = $userRepository->findOneBy(['username' => 'admin']);

        $this->client->loginUser($userAdmin);

        $crawler = $this->client->request('GET', '/profile');
        $this->assertPageTitleSame('Stock Exchange - Profile');
        $this->assertSelectorTextSame('h2', "Welcome, {$userAdmin->getUsername()}!");
        $this->assertSelectorTextSame('test', "W, {$userAdmin->getUsername()}");
        //$this->assertSelectorTextContains('p[class=',);

        //$this->portfolioFixture->getReference(PortfolioFixture::PORTFOLIO_ADMIN_REFERENCE, PortfolioFixture::class);
        //$this->assertSelectorTextContains();
    }

    protected function tearDown(): void
    {
        $this->executor->purge();
        parent::tearDown();
    }
}
